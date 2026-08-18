<?php

namespace Database\Seeders;

use App\Models\CustomerProfile;
use App\Models\Machine;
use App\Models\Service;
use App\Models\StaffProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);

        // 1. Staff User
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
                'position' => 'Regular',
                'hire_date' => '2024-01-15',
                'status' => 'active',
            ]
        );

        // 2b. Rider User: Anthony Cayme
        $rider = User::firstOrCreate(
            ['email' => 'caymeanthony1@gmail.com'],
            [
                'name' => 'Anthony Cayme',
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
            ]
        );

        // 4. Customer User 2: Alexa Cas
        $customer2 = User::firstOrCreate(
            ['email' => 'casalexa10@gmail.com'],
            [
                'name' => 'Alexa Cas',
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
            ['machine_code' => 'WM-001', 'machine_name' => 'Machine 1', 'machine_type' => 'washer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-002', 'machine_name' => 'Machine 2', 'machine_type' => 'washer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'DR-001', 'machine_name' => 'Machine 3', 'machine_type' => 'dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WD-001', 'machine_name' => 'Machine 4', 'machine_type' => 'washer_dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-003', 'machine_name' => 'Machine 5', 'machine_type' => 'washer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-004', 'machine_name' => 'Machine 6', 'machine_type' => 'washer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'DR-002', 'machine_name' => 'Machine 7', 'machine_type' => 'dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WD-002', 'machine_name' => 'Machine 8', 'machine_type' => 'washer_dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-005', 'machine_name' => 'Machine 9', 'machine_type' => 'washer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-006', 'machine_name' => 'Machine 10', 'machine_type' => 'washer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'DR-003', 'machine_name' => 'Machine 11', 'machine_type' => 'dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'DR-004', 'machine_name' => 'Machine 12', 'machine_type' => 'dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-007', 'machine_name' => 'Machine 13', 'machine_type' => 'washer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-008', 'machine_name' => 'Machine 14', 'machine_type' => 'washer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'DR-005', 'machine_name' => 'Machine 15', 'machine_type' => 'dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WD-003', 'machine_name' => 'Machine 16', 'machine_type' => 'washer_dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-009', 'machine_name' => 'Machine 17', 'machine_type' => 'washer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WM-010', 'machine_name' => 'Machine 18', 'machine_type' => 'washer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'DR-006', 'machine_name' => 'Machine 19', 'machine_type' => 'dryer', 'status' => 'idle', 'remaining_minutes' => null],
            ['machine_code' => 'WD-004', 'machine_name' => 'Machine 20', 'machine_type' => 'washer_dryer', 'status' => 'idle', 'remaining_minutes' => null],
        ];

        foreach ($machines as $m) {
            Machine::firstOrCreate(['machine_code' => $m['machine_code']], $m);
        }
    }
}
