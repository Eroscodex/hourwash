<?php

use App\Models\Machine;
use App\Models\Order;
use App\Models\User;

test('admin can update order status to washing and sync machine', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $customer = User::factory()->create(['role' => 'customer']);
    $machine = Machine::create([
        'machine_code' => 'WM-TEST-01',
        'machine_name' => 'Test Machine 1',
        'machine_type' => 'washer',
        'status' => 'idle',
    ]);

    $order = Order::create([
        'order_number' => 'HW-TEST001',
        'customer_id' => $customer->id,
        'machine_id' => $machine->id,
        'weight_kg' => 7.0,
        'subtotal' => 150.0,
        'total_amount' => 150.0,
        'order_status' => 'received',
        'payment_status' => 'unpaid',
    ]);

    $response = $this
        ->actingAs($admin)
        ->patch(route('admin.laundry.update', $order->id), [
            'status' => 'washing',
            'payment_status' => 'paid',
        ]);

    $response->assertSessionHasNoErrors();
    $response->assertRedirect();

    $this->assertDatabaseHas('orders', [
        'id' => $order->id,
        'order_status' => 'washing',
        'payment_status' => 'paid',
    ]);

    $this->assertDatabaseHas('machines', [
        'id' => $machine->id,
        'status' => 'washing',
        'current_order_id' => $order->id,
    ]);
});
