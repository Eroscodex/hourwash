<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // Step 1: Temporarily expand the ENUM to include both old and new values
            DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM(
                'pending', 'received', 'washing', 'rinsing', 'drying', 'ready', 'picked_up', 'delivering', 'delivered', 'completed', 'cancelled',
                'out_for_pickup', 'finish', 'out_for_delivery'
            ) DEFAULT 'pending'");

            DB::statement("ALTER TABLE order_status_history MODIFY COLUMN status ENUM(
                'pending', 'received', 'washing', 'rinsing', 'drying', 'ready', 'picked_up', 'delivering', 'delivered', 'completed', 'cancelled',
                'out_for_pickup', 'finish', 'out_for_delivery'
            )");

            // Step 2: Safely map old values to the new enum values
            DB::table('orders')->where('order_status', 'ready')->update(['order_status' => 'finish']);
            DB::table('orders')->where('order_status', 'picked_up')->update(['order_status' => 'completed']);
            DB::table('orders')->where('order_status', 'delivering')->update(['order_status' => 'out_for_delivery']);
            DB::table('orders')->where('order_status', 'delivered')->update(['order_status' => 'completed']);

            DB::table('order_status_history')->where('status', 'ready')->update(['status' => 'finish']);
            DB::table('order_status_history')->where('status', 'picked_up')->update(['status' => 'completed']);
            DB::table('order_status_history')->where('status', 'delivering')->update(['status' => 'out_for_delivery']);
            DB::table('order_status_history')->where('status', 'delivered')->update(['status' => 'completed']);

            // Step 3: Shrink the ENUM definition to ONLY allow the new values
            DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM(
                'pending', 'out_for_pickup', 'received', 'washing', 'rinsing', 'drying', 'finish', 'out_for_delivery', 'completed', 'cancelled'
            ) DEFAULT 'pending'");

            DB::statement("ALTER TABLE order_status_history MODIFY COLUMN status ENUM(
                'pending', 'out_for_pickup', 'received', 'washing', 'rinsing', 'drying', 'finish', 'out_for_delivery', 'completed', 'cancelled'
            )");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('pending', 'received', 'washing', 'rinsing', 'drying', 'ready', 'picked_up', 'delivering', 'delivered', 'completed', 'cancelled') DEFAULT 'pending'");
            DB::statement("ALTER TABLE order_status_history MODIFY COLUMN status ENUM('pending', 'received', 'washing', 'rinsing', 'drying', 'ready', 'picked_up', 'delivering', 'delivered', 'completed', 'cancelled')");
        }
    }
};
