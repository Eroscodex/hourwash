<?php

namespace Database\Seeders;

use App\Models\CustomerFeedback;
use App\Models\CustomerProfile;
use App\Models\InventoryItem;
use App\Models\Machine;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Promotion;
use App\Models\QrCode;
use App\Models\Service;
use App\Models\StaffProfile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin / Owner User
        $owner = User::firstOrCreate(
            ['email' => 'karlnicko2019@gmail.com'],
            [
                'name' => 'Karl Nicko',
                'password' => Hash::make('Karlnicko0202!'),
                'phone' => '09123456789',
                'role' => 'owner',
                'status' => 'active',
            ]
        );

        // 2. Staff User
        $staff = User::firstOrCreate(
            ['email' => 'shayneformento@gmail.com'],
            [
                'name' => 'Shayne Formento',
                'password' => Hash::make('Shayne1234!'),
                'phone' => '09123456781',
                'role' => 'staff',
                'status' => 'active',
            ]
        );

        StaffProfile::firstOrCreate(
            ['user_id' => $staff->id],
            [
                'employee_id' => 'EMP-001',
                'position' => 'Senior Laundry Specialist',
                'hire_date' => '2024-01-15',
                'status' => 'active',
            ]
        );

        // 3. Customer User 1: Lezil Orgasa
        $customer1 = User::firstOrCreate(
            ['email' => 'lezorgasa@gmail.com'],
            [
                'name' => 'Lezil Orgasa',
                'password' => Hash::make('Lezil1234!'),
                'phone' => '09123456782',
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        CustomerProfile::firstOrCreate(
            ['user_id' => $customer1->id],
            [
                'address' => 'Magallanes St., Orosite',
                'city' => 'Legazpi City',
                'province' => 'Albay',
                'loyalty_points' => 250,
            ]
        );

        // 4. Customer User 2: Alexa Casa
        $customer2 = User::firstOrCreate(
            ['email' => 'casalexa10@gmail.com'],
            [
                'name' => 'Alexa Casa',
                'password' => Hash::make('Alexa1234!'),
                'phone' => '09123456783',
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        CustomerProfile::firstOrCreate(
            ['user_id' => $customer2->id],
            [
                'address' => 'Rizal St., Cabangan',
                'city' => 'Legazpi City',
                'province' => 'Albay',
                'loyalty_points' => 150,
            ]
        );

        // 5. Laundry Services
        $services = [
            [
                'name' => 'Wash & Dry',
                'description' => 'Complete washing and drying service.',
                'service_type' => 'wash_dry',
                'price' => 120.00,
                'price_unit' => 'kg',
                'estimated_minutes' => 120,
                'status' => 'active',
            ],
            [
                'name' => 'Premium Wash',
                'description' => 'Premium cleaning service for regular clothing.',
                'service_type' => 'wash',
                'price' => 100.00,
                'price_unit' => 'kg',
                'estimated_minutes' => 90,
                'status' => 'active',
            ],
            [
                'name' => 'Wash, Dry & Fold',
                'description' => 'Complete wash, dry and folding service.',
                'service_type' => 'wash_dry_fold',
                'price' => 200.00,
                'price_unit' => 'kg',
                'estimated_minutes' => 150,
                'status' => 'active',
            ],
            [
                'name' => 'Comforters & Blankets',
                'description' => 'Special care for large laundry items.',
                'service_type' => 'blanket',
                'price' => 250.00,
                'price_unit' => 'item',
                'estimated_minutes' => 180,
                'status' => 'active',
            ],
            [
                'name' => 'Pickup & Delivery',
                'description' => 'Laundry pickup and delivery service.',
                'service_type' => 'pickup_delivery',
                'price' => 50.00,
                'price_unit' => 'service',
                'estimated_minutes' => 30,
                'status' => 'active',
            ],
        ];

        foreach ($services as $srv) {
            Service::firstOrCreate(['name' => $srv['name']], $srv);
        }

        // 6. Machines (Full 20-Machine Store Fleet)
        $machines = [
            ['machine_code' => 'WM-001', 'machine_name' => 'Machine 1', 'machine_type' => 'washer', 'status' => 'washing', 'remaining_minutes' => 28],
            ['machine_code' => 'WM-002', 'machine_name' => 'Machine 2', 'machine_type' => 'washer', 'status' => 'rinsing', 'remaining_minutes' => 15],
            ['machine_code' => 'DR-001', 'machine_name' => 'Machine 3', 'machine_type' => 'dryer', 'status' => 'drying', 'remaining_minutes' => 35],
            ['machine_code' => 'WD-001', 'machine_name' => 'Machine 4', 'machine_type' => 'washer_dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-003', 'machine_name' => 'Machine 5', 'machine_type' => 'washer', 'status' => 'washing', 'remaining_minutes' => 42],
            ['machine_code' => 'WM-004', 'machine_name' => 'Machine 6', 'machine_type' => 'washer', 'status' => 'maintenance', 'maintenance_note' => 'Check required', 'remaining_minutes' => null],
            ['machine_code' => 'DR-002', 'machine_name' => 'Machine 7', 'machine_type' => 'dryer', 'status' => 'drying', 'remaining_minutes' => 20],
            ['machine_code' => 'WD-002', 'machine_name' => 'Machine 8', 'machine_type' => 'washer_dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-005', 'machine_name' => 'Machine 9', 'machine_type' => 'washer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-006', 'machine_name' => 'Machine 10', 'machine_type' => 'washer', 'status' => 'washing', 'remaining_minutes' => 38],
            ['machine_code' => 'DR-003', 'machine_name' => 'Machine 11', 'machine_type' => 'dryer', 'status' => 'drying', 'remaining_minutes' => 45],
            ['machine_code' => 'DR-004', 'machine_name' => 'Machine 12', 'machine_type' => 'dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-007', 'machine_name' => 'Machine 13', 'machine_type' => 'washer', 'status' => 'rinsing', 'remaining_minutes' => 10],
            ['machine_code' => 'WM-008', 'machine_name' => 'Machine 14', 'machine_type' => 'washer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'DR-005', 'machine_name' => 'Machine 15', 'machine_type' => 'dryer', 'status' => 'drying', 'remaining_minutes' => 25],
            ['machine_code' => 'WD-003', 'machine_name' => 'Machine 16', 'machine_type' => 'washer_dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-009', 'machine_name' => 'Machine 17', 'machine_type' => 'washer', 'status' => 'washing', 'remaining_minutes' => 50],
            ['machine_code' => 'WM-010', 'machine_name' => 'Machine 18', 'machine_type' => 'washer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'DR-006', 'machine_name' => 'Machine 19', 'machine_type' => 'dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WD-004', 'machine_name' => 'Machine 20', 'machine_type' => 'washer_dryer', 'status' => 'idle', 'remaining_minutes' => null],
        ];

        foreach ($machines as $m) {
            Machine::firstOrCreate(['machine_code' => $m['machine_code']], $m);
        }

        // 7. Inventory Items
        $inventory = [
            ['name' => 'Laundry Detergent', 'category' => 'Cleaning Supplies', 'unit' => 'liter', 'quantity' => 50.00, 'minimum_stock' => 10.00, 'unit_cost' => 120.00, 'status' => 'in_stock'],
            ['name' => 'Fabric Softener', 'category' => 'Cleaning Supplies', 'unit' => 'liter', 'quantity' => 35.00, 'minimum_stock' => 10.00, 'unit_cost' => 110.00, 'status' => 'in_stock'],
            ['name' => 'Bleach', 'category' => 'Cleaning Supplies', 'unit' => 'liter', 'quantity' => 20.00, 'minimum_stock' => 5.00, 'unit_cost' => 90.00, 'status' => 'in_stock'],
            ['name' => 'Laundry Bags', 'category' => 'Packaging', 'unit' => 'piece', 'quantity' => 100.00, 'minimum_stock' => 20.00, 'unit_cost' => 5.00, 'status' => 'in_stock'],
            ['name' => 'Receipt Paper', 'category' => 'Supplies', 'unit' => 'roll', 'quantity' => 10.00, 'minimum_stock' => 3.00, 'unit_cost' => 35.00, 'status' => 'in_stock'],
        ];

        foreach ($inventory as $inv) {
            InventoryItem::firstOrCreate(['name' => $inv['name']], $inv);
        }

        // 8. Promotions
        Promotion::firstOrCreate(
            ['code' => 'CLEAN20'],
            [
                'name' => '20% Off Laundry',
                'description' => '20 percent discount for eligible orders.',
                'discount_type' => 'percentage',
                'discount_value' => 20.00,
                'minimum_amount' => 100.00,
                'start_date' => '2026-01-01 00:00:00',
                'end_date' => '2026-12-31 23:59:59',
                'usage_limit' => 500,
                'status' => 'active',
            ]
        );

        // 9. Sample Active Orders Linked to Machines
        $washService = Service::where('service_type', 'wash_dry')->first();
        $premService = Service::where('service_type', 'wash')->first();
        $foldService = Service::where('service_type', 'wash_dry_fold')->first();

        $activeOrdersData = [
            ['code' => 'HW884210', 'm_code' => 'WM-001', 'cust' => $customer1, 'status' => 'washing', 'min' => 28, 'service' => $washService],
            ['code' => 'HW729104', 'm_code' => 'WM-002', 'cust' => $customer2, 'status' => 'rinsing', 'min' => 15, 'service' => $premService],
            ['code' => 'HW541092', 'm_code' => 'DR-001', 'cust' => $customer1, 'status' => 'drying', 'min' => 35, 'service' => $foldService],
            ['code' => 'HW903115', 'm_code' => 'WM-003', 'cust' => $customer2, 'status' => 'washing', 'min' => 42, 'service' => $washService],
            ['code' => 'HW618302', 'm_code' => 'DR-002', 'cust' => $customer1, 'status' => 'drying', 'min' => 20, 'service' => $foldService],
            ['code' => 'HW886006', 'm_code' => 'WM-006', 'cust' => $customer2, 'status' => 'washing', 'min' => 38, 'service' => $washService],
            ['code' => 'HW543003', 'm_code' => 'DR-003', 'cust' => $customer1, 'status' => 'drying', 'min' => 45, 'service' => $foldService],
            ['code' => 'HW727007', 'm_code' => 'WM-007', 'cust' => $customer2, 'status' => 'rinsing', 'min' => 10, 'service' => $premService],
            ['code' => 'HW545005', 'm_code' => 'DR-005', 'cust' => $customer1, 'status' => 'drying', 'min' => 25, 'service' => $foldService],
            ['code' => 'HW889009', 'm_code' => 'WM-009', 'cust' => $customer2, 'status' => 'washing', 'min' => 50, 'service' => $washService],
        ];

        foreach ($activeOrdersData as $data) {
            $machine = Machine::where('machine_code', $data['m_code'])->first();

            $order = Order::firstOrCreate(
                ['order_number' => $data['code']],
                [
                    'customer_id' => $data['cust']->id,
                    'service_id' => $data['service']?->id,
                    'machine_id' => $machine?->id,
                    'weight_kg' => 5.00,
                    'subtotal' => 120.00,
                    'total_amount' => 120.00,
                    'payment_status' => 'paid',
                    'order_status' => $data['status'],
                    'pickup_type' => 'drop_off',
                    'estimated_completion' => Carbon::now()->addMinutes($data['min']),
                    'notes' => 'Handle with extra care.',
                ]
            );

            if ($machine) {
                $machine->update([
                    'current_order_id' => $order->id,
                    'remaining_minutes' => $data['min'],
                ]);
            }

            QrCode::firstOrCreate(
                ['order_id' => $order->id],
                [
                    'qr_token' => $data['code'],
                    'status' => 'active',
                ]
            );
        }

        // Sample Notifications
        Notification::firstOrCreate(
            ['user_id' => $customer1->id, 'title' => 'Machine 1 Started Washing'],
            [
                'message' => 'Order #HW884210 is now being washed in Machine 1.',
                'type' => 'machine',
                'reference_id' => 1,
            ]
        );

        // Sample Customer Feedbacks & Ratings
        CustomerFeedback::firstOrCreate(
            ['user_id' => $customer1->id, 'comment' => 'Super clean and fragrant clothes! Scanned the QR code on my bag and tracked live cleaning status.'],
            [
                'order_id' => 1,
                'rating' => 5,
                'status' => 'published',
            ]
        );

        CustomerFeedback::firstOrCreate(
            ['user_id' => $customer2->id, 'comment' => 'Fast drying and excellent service. Staff was friendly and my blankets came out smelling fresh!'],
            [
                'order_id' => 2,
                'rating' => 5,
                'status' => 'published',
            ]
        );
    }
}
