<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (! Schema::hasColumn('orders', 'payment_method')) {
                    $table->string('payment_method', 50)->default('cash')->after('payment_status');
                }

                if (! Schema::hasColumn('orders', 'subtotal')) {
                    $table->decimal('subtotal', 10, 2)->default(0.00)->after('weight_kg');
                }

                if (! Schema::hasColumn('orders', 'delivery_fee')) {
                    $table->decimal('delivery_fee', 10, 2)->default(0.00)->after('subtotal');
                }

                if (! Schema::hasColumn('orders', 'discount')) {
                    $table->decimal('discount', 10, 2)->default(0.00)->after('delivery_fee');
                }

                if (! Schema::hasColumn('orders', 'paid_at')) {
                    $table->dateTime('paid_at')->nullable()->after('payment_method');
                }

                if (! Schema::hasColumn('orders', 'pickup_type')) {
                    $table->string('pickup_type', 50)->default('drop_off')->after('order_status');
                }

                if (! Schema::hasColumn('orders', 'pickup_date')) {
                    $table->date('pickup_date')->nullable()->after('pickup_type');
                }

                if (! Schema::hasColumn('orders', 'pickup_time')) {
                    $table->time('pickup_time')->nullable()->after('pickup_date');
                }

                if (! Schema::hasColumn('orders', 'notes')) {
                    $table->text('notes')->nullable()->after('completed_at');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (Schema::hasColumn('orders', 'payment_method')) {
                    $table->dropColumn('payment_method');
                }
            });
        }
    }
};
