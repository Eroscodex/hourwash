<?php

namespace Database\Seeders;

use App\Models\CustomerProfile;
use App\Models\StaffProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin User
        $this->call(AdminUserSeeder::class);

        // 2. Staff User: Shayne Formento
        $staff = User::updateOrCreate(
            ['email' => 'shayneformento@gmail.com'],
            [
                'name' => 'Shayne Formento',
                'password' => Hash::make('Shayne1234!'),
                'phone' => '09123456781',
                'role' => 'staff',
                'status' => 'active',
            ]
        );

        StaffProfile::updateOrCreate(
            ['employee_id' => 'EMP-001'],
            [
                'user_id' => $staff->id,
                'position' => 'Regular',
                'hire_date' => '2024-01-15',
                'status' => 'active',
            ]
        );

        // 3. Rider User: Anthony Cayme
        User::updateOrCreate(
            ['email' => 'caymeanthony1@gmail.com'],
            [
                'name' => 'Anthony Cayme',
                'password' => Hash::make('Anthony1234!'),
                'phone' => '09100317744',
                'role' => 'rider',
                'status' => 'active',
            ]
        );

        // 4. Customer User 1: Lezil Orgasa
        $customer1 = User::updateOrCreate(
            ['email' => 'lezorgasa@gmail.com'],
            [
                'name' => 'Lezil Orgasa',
                'password' => Hash::make('Lezil1234!'),
                'phone' => '09123456782',
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        CustomerProfile::updateOrCreate(
            ['user_id' => $customer1->id],
            [
                'address' => 'Magallanes St., Orosite',
                'city' => 'Legazpi City',
                'province' => 'Albay',
            ]
        );

        // 5. Customer User 2: Alexa Cas
        $customer2 = User::updateOrCreate(
            ['email' => 'casalexa10@gmail.com'],
            [
                'name' => 'Alexa Cas',
                'password' => Hash::make('Alexa1234!'),
                'phone' => '09123456783',
                'role' => 'customer',
                'status' => 'active',
            ]
        );

        CustomerProfile::updateOrCreate(
            ['user_id' => $customer2->id],
            [
                'address' => 'Rizal St., Cabangan',
                'city' => 'Legazpi City',
                'province' => 'Albay',
            ]
        );

        // 6. Laundry Services (Official Hour Wash Packages & Pricing)
        $this->call(OfficialServicesSeeder::class);

        // 7. Machines (Full 20 Commercial Washer & Dryer Combo Machine Fleet)
        $this->call(CleanMachineFleetSeeder::class);
    }
}
