<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM(
                'pending', 'out_for_pickup', 'received', 'washing', 'rinsing', 'drying', 'done', 'finish', 'out_for_delivery', 'completed', 'cancelled'
            ) DEFAULT 'pending'");

            DB::statement("ALTER TABLE order_status_history MODIFY COLUMN status ENUM(
                'pending', 'out_for_pickup', 'received', 'washing', 'rinsing', 'drying', 'done', 'finish', 'out_for_delivery', 'completed', 'cancelled'
            )");

            DB::table('orders')->where('order_status', 'done')->update(['order_status' => 'finish']);
            DB::table('order_status_history')->where('status', 'done')->update(['status' => 'finish']);

            DB::statement("ALTER TABLE orders MODIFY COLUMN order_status ENUM(
                'pending', 'out_for_pickup', 'received', 'washing', 'rinsing', 'drying', 'finish', 'out_for_delivery', 'completed', 'cancelled'
            ) DEFAULT 'pending'");

            DB::statement("ALTER TABLE order_status_history MODIFY COLUMN status ENUM(
                'pending', 'out_for_pickup', 'received', 'washing', 'rinsing', 'drying', 'finish', 'out_for_delivery', 'completed', 'cancelled'
            )");
        } else {
            DB::table('orders')->where('order_status', 'done')->update(['order_status' => 'finish']);
            DB::table('order_status_history')->where('status', 'done')->update(['status' => 'finish']);
        }
    }

    public function down(): void
    {
        DB::table('orders')->where('order_status', 'finish')->update(['order_status' => 'done']);
        DB::table('order_status_history')->where('status', 'finish')->update(['status' => 'done']);
    }
};
