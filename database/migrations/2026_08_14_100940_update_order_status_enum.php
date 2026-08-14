<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            // 1. Update existing orders to map old statuses to new statuses first
            DB::table('orders')->where('order_status', 'ready')->update(['order_status' => 'done']);
            DB::table('orders')->where('order_status', 'picked_up')->update(['order_status' => 'completed']);
            DB::table('orders')->where('order_status', 'delivering')->update(['order_status' => 'out_for_delivery']);
            DB::table('orders')->where('order_status', 'delivered')->update(['order_status' => 'completed']);

            // 2. Also update status history
            DB::table('order_status_history')->where('status', 'ready')->update(['status' => 'done']);
            DB::table('order_status_history')->where('status', 'picked_up')->update(['status' => 'completed']);
            DB::table('order_status_history')->where('status', 'delivering')->update(['status' => 'out_for_delivery']);
            DB::table('order_status_history')->where('status', 'delivered')->update(['status' => 'completed']);

            // 3. Now modify columns
            Schema::table('orders', function (Blueprint $table) {
                DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('pending', 'out_for_pickup', 'received', 'washing', 'rinsing', 'drying', 'done', 'out_for_delivery', 'completed', 'cancelled') DEFAULT 'pending'");
            });

            Schema::table('order_status_history', function (Blueprint $table) {
                DB::statement("ALTER TABLE order_status_history MODIFY COLUMN status ENUM('pending', 'out_for_pickup', 'received', 'washing', 'rinsing', 'drying', 'done', 'out_for_delivery', 'completed', 'cancelled')");
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            Schema::table('orders', function (Blueprint $table) {
                DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM('pending', 'received', 'washing', 'rinsing', 'drying', 'ready', 'picked_up', 'delivering', 'delivered', 'completed', 'cancelled') DEFAULT 'pending'");
            });

            Schema::table('order_status_history', function (Blueprint $table) {
                DB::statement("ALTER TABLE order_status_history MODIFY COLUMN status ENUM('pending', 'received', 'washing', 'rinsing', 'drying', 'ready', 'picked_up', 'delivering', 'delivered', 'completed', 'cancelled')");
            });
        }
    }
};
