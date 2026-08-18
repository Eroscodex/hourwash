<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {

        User::firstOrCreate(
            ['email' => 'karlnicko2019@gmail.com'],
            [
                'name' => 'Administrator',
                'phone' => '09123456780',
                'password' => Hash::make('Karlnicko1234!'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );

    }
}
