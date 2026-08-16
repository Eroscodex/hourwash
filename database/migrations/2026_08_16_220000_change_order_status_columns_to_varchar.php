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
                DB::statement("ALTER TABLE order_status_history MODIFY COLUMN status VARCHAR(50) DEFAULT 'pending'");
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
