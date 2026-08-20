<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('services')) {
            try {
                DB::statement("ALTER TABLE services MODIFY COLUMN service_type VARCHAR(50) NOT NULL DEFAULT 'wash_dry'");
                DB::statement("ALTER TABLE services MODIFY COLUMN price_unit VARCHAR(50) NOT NULL DEFAULT 'load'");
            } catch (Throwable $e) {
                // Ignore driver-specific alter errors
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
