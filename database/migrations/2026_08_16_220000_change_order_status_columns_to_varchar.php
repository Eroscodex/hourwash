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
            try {
                DB::statement("ALTER TABLE orders MODIFY COLUMN order_status VARCHAR(50) DEFAULT 'pending'");
                DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status VARCHAR(50) DEFAULT 'unpaid'");
                DB::statement("ALTER TABLE services MODIFY COLUMN service_type VARCHAR(50) DEFAULT 'wash_dry'");
                DB::statement("ALTER TABLE services MODIFY COLUMN price_unit VARCHAR(50) DEFAULT 'kg'");
            } catch (Throwable $e) {
                // Ignore if driver does not support alter modify
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
