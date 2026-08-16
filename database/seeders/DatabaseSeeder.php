<?php

namespace Database\Seeders;

use App\Models\CustomerFeedback;
use App\Models\CustomerProfile;
use App\Models\EmailNotification;
use App\Models\InventoryItem;
use App\Models\Machine;
use App\Models\Notification;
use App\Models\Order;
use App\Models\Promotion;
use App\Models\QrCode;
use App\Models\Service;
use App\Models\SmsNotification;
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
            ['employee_id' => 'EMP-001'],
            [
                'user_id' => $staff->id,
                'position' => 'Senior Laundry Specialist',
                'hire_date' => '2024-01-15',
                'status' => 'active',
            ]
        );

        // 2b. Rider User: Anthony
        $rider = User::firstOrCreate(
            ['email' => 'caymeanthony1@gmail.com'],
            [
                'name' => 'Anthony',
                'password' => Hash::make('Anthony1234!'),
                'phone' => '09100317744',
                'role' => 'rider',
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
            ['code' => 'HW884210', 'm_code' => 'WM-001', 'cust' => $customer1, 'status' => 'out_for_pickup', 'min' => 28, 'service' => $washService, 'pickup_type' => 'pickup_delivery'],
            ['code' => 'HW729104', 'm_code' => 'WM-002', 'cust' => $customer2, 'status' => 'rinsing', 'min' => 15, 'service' => $premService, 'pickup_type' => 'drop_off'],
            ['code' => 'HW541092', 'm_code' => 'DR-001', 'cust' => $customer1, 'status' => 'out_for_delivery', 'min' => 35, 'service' => $foldService, 'pickup_type' => 'pickup_delivery'],
            ['code' => 'HW903115', 'm_code' => 'WM-003', 'cust' => $customer2, 'status' => 'out_for_pickup', 'min' => 42, 'service' => $washService, 'pickup_type' => 'pickup_delivery'],
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
                    'pickup_type' => $data['pickup_type'] ?? 'drop_off',
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
        $order1 = Order::where('order_number', 'HW884210')->first();
        $order2 = Order::where('order_number', 'HW729104')->first();

        if ($order1) {
            CustomerFeedback::firstOrCreate(
                ['order_id' => $order1->id],
                [
                    'user_id' => $customer1->id,
                    'comment' => 'Super clean and fragrant clothes! Scanned the QR code on my bag and tracked live cleaning status.',
                    'rating' => 5,
                    'status' => 'published',
                ]
            );
        }

        if ($order2) {
            CustomerFeedback::firstOrCreate(
                ['order_id' => $order2->id],
                [
                    'user_id' => $customer2->id,
                    'comment' => 'Fast drying and excellent service. Staff was friendly and my blankets came out smelling fresh!',
                    'rating' => 5,
                    'status' => 'published',
                ]
            );
        }

        // 11. Sample SMS Outbox Logs
        $sampleSmsList = [
            ['phone' => '09123456782', 'msg' => 'HourWash Alert: Hi Lezil Orgasa, your laundry Order #HW-QAQ3SM2V status is now WASHING. Est Completion: Aug 15, 2026 04:28 PM. Track live: https://hourwashlaundryshop.up.railway.app/laundry/track/HW-QAQ3SM2V'],
            ['phone' => '09123456782', 'msg' => 'HourWash Alert: Hi Lezil Orgasa, your laundry Order #HW-QAQ3SM2V status is now PENDING. Est Completion: Aug 15, 2026 04:28 PM. Track live: https://hourwashlaundryshop.up.railway.app/laundry/track/HW-QAQ3SM2V'],
            ['phone' => '09222555100', 'msg' => 'HourWash Alert: Hi mark uno, your laundry Order #HW-TBGGCIRT status is now OUT FOR PICKUP. Est Completion: Aug 15, 2026 04:44 PM. Track live: https://hourwashlaundryshop.up.railway.app/laundry/track/HW-TBGGCIRT'],
            ['phone' => '09222555100', 'msg' => 'HourWash Alert: Hi mark uno, your laundry Order #HW-TBGGCIRT status is now PENDING. Est Completion: Aug 15, 2026 01:44 PM. Track live: https://hourwashlaundryshop.up.railway.app/laundry/track/HW-TBGGCIRT'],
            ['phone' => '09051378154', 'msg' => 'HourWash Alert: Hi Eroscodex, your laundry Order #HW-3XLDNZ3O status is now OUT FOR PICKUP. Est Completion: Aug 14, 2026 07:00 PM. Track live: https://hourwashlaundryshop.up.railway.app/laundry/track/HW-3XLDNZ3O'],
            ['phone' => '09100317744', 'msg' => 'HourWash Alert: Hi Eroscodex, your laundry Order #HW-3XLDNZ3O status is now PENDING. Est Completion: Aug 14, 2026 07:00 PM. Track live: https://hourwashlaundryshop.up.railway.app/laundry/track/HW-3XLDNZ3O'],
            ['phone' => '09051378154', 'msg' => 'HourWash Alert: Hi Alexa Casa, your laundry Order #HW903115 status is now RECEIVED. Est Completion: Aug 09, 2026 04:36 PM. Track live: https://hourwashlaundryshop.up.railway.app/laundry/track/HW903115'],
            ['phone' => '09175012581', 'msg' => 'HourWash Alert: Hi Alma Alondra, your laundry Order #HW-QAZGIDEE status is now WASHING. Est Completion: Aug 12, 2026 09:58 PM. Track live: https://hourwashlaundryshop.up.railway.app/laundry/track/HW-QAZGIDEE'],
            ['phone' => '09175012581', 'msg' => 'HourWash Alert: Hi Alma Alondra, your laundry Order #HW-QAZGIDEE status is now RECEIVED. Est Completion: Aug 12, 2026 09:58 PM. Track live: https://hourwashlaundryshop.up.railway.app/laundry/track/HW-QAZGIDEE'],
            ['phone' => '09175012581', 'msg' => 'HourWash Alert: Hi Alma Alondra, your laundry Order #HW-QAZGIDEE status is now OUT FOR PICKUP. Est Completion: Aug 12, 2026 09:58 PM. Track live: https://hourwashlaundryshop.up.railway.app/laundry/track/HW-QAZGIDEE'],
        ];

        foreach ($sampleSmsList as $sms) {
            SmsNotification::firstOrCreate(
                ['message' => $sms['msg']],
                [
                    'phone' => $sms['phone'],
                    'status' => 'sent',
                    'user_id' => $customer1->id,
                    'order_id' => $order1?->id,
                ]
            );
        }

        // 12. Sample Email Outbox Logs
        $sampleEmailList = [
            [
                'recipient' => 'lezorgasa@gmail.com',
                'subject' => 'HourWash Notification: Order #HW884210 is WASHING',
                'body' => 'HourWash Alert: Hi Lezil Orgasa, your laundry Order #HW884210 status is now WASHING. Est Completion: Aug 16, 2026 04:28 PM. Track live: https://hourwashlaundryshop.up.railway.app/laundry/track/HW884210',
            ],
            [
                'recipient' => 'casalexa10@gmail.com',
                'subject' => 'HourWash Notification: Order #HW729104 is RINSING',
                'body' => 'HourWash Alert: Hi Alexa Casa, your laundry Order #HW729104 status is now RINSING. Est Completion: Aug 16, 2026 04:15 PM. Track live: https://hourwashlaundryshop.up.railway.app/laundry/track/HW729104',
            ],
            [
                'recipient' => 'karlnicko2019@gmail.com',
                'subject' => 'HourWash Admin Alert: New Order #HW884210 Received',
                'body' => 'New customer laundry order #HW884210 has been received and assigned to Machine 1.',
            ],
        ];

        foreach ($sampleEmailList as $email) {
            EmailNotification::firstOrCreate(
                ['subject' => $email['subject']],
                [
                    'recipient' => $email['recipient'],
                    'body' => $email['body'],
                    'status' => 'sent',
                    'user_id' => $customer1->id,
                    'order_id' => $order1?->id,
                ]
            );
        }
    }
}
