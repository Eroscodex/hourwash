<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Change users.role column to VARCHAR(50) to support 'rider' role on MySQL
        if (Schema::hasTable('users') && DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `users` MODIFY COLUMN `role` VARCHAR(50) NOT NULL DEFAULT 'customer'");
        }

        // 2. Create or update Rider User: Anthony
        User::updateOrCreate(
            ['email' => 'caymeanthony1@gmail.com'],
            [
                'name' => 'Anthony',
                'password' => Hash::make('Anthony1234!'),
                'phone' => '09100317744',
                'role' => 'rider',
                'status' => 'active',
            ]
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        User::where('email', 'caymeanthony1@gmail.com')->delete();
    }
};
