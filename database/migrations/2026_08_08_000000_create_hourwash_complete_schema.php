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
        Schema::dropIfExists('laundries');
        Schema::dropIfExists('machines');

        // 2. CUSTOMER PROFILES
        Schema::create('customer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('address')->nullable();
            $table->string('barangay', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('province', 100)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('loyalty_points')->default(0);
            $table->timestamps();
        });

        // 3. STAFF PROFILES
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('employee_id', 50)->unique();
            $table->string('position', 100)->default('Staff');
            $table->date('hire_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'on_leave'])->default('active');
            $table->timestamps();
        });

        // 4. SERVICES
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->enum('service_type', ['wash', 'dry', 'wash_dry', 'wash_dry_fold', 'blanket', 'pickup_delivery', 'other'])->default('wash_dry');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->enum('price_unit', ['kg', 'load', 'item', 'service'])->default('kg');
            $table->unsignedInteger('estimated_minutes')->default(60);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // 5. MACHINES
        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->string('machine_code', 50)->unique();
            $table->string('machine_name', 100);
            $table->enum('machine_type', ['washer', 'dryer', 'washer_dryer'])->default('washer');
            $table->enum('status', ['idle', 'washing', 'rinsing', 'drying', 'maintenance', 'offline'])->default('idle');
            $table->unsignedBigInteger('current_order_id')->nullable()->index();
            $table->unsignedInteger('remaining_minutes')->nullable();
            $table->text('maintenance_note')->nullable();
            $table->timestamp('last_status_update')->useCurrent();
            $table->timestamps();
        });

        // 6. ORDERS
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 50)->unique();
            $table->foreignId('customer_id')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('machine_id')->nullable()->constrained('machines')->nullOnDelete()->cascadeOnUpdate();
            $table->decimal('weight_kg', 8, 2)->default(0.00);
            $table->decimal('subtotal', 10, 2)->default(0.00);
            $table->decimal('delivery_fee', 10, 2)->default(0.00);
            $table->decimal('discount', 10, 2)->default(0.00);
            $table->decimal('total_amount', 10, 2)->default(0.00);
            $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'partial', 'refunded', 'failed'])->default('unpaid');
            $table->enum('order_status', [
                'pending', 'out_for_pickup', 'received', 'washing', 'rinsing', 'drying',
                'finish', 'out_for_delivery', 'completed', 'cancelled',
            ])->default('pending');
            $table->enum('pickup_type', ['drop_off', 'pickup', 'delivery', 'pickup_delivery'])->default('drop_off');
            $table->date('pickup_date')->nullable();
            $table->time('pickup_time')->nullable();
            $table->dateTime('estimated_completion')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('order_status');
            $table->index('payment_status');
            $table->index('created_at');
        });

        // Add machine -> current_order_id foreign key constraint
        Schema::table('machines', function (Blueprint $table) {
            $table->foreign('current_order_id')->references('id')->on('orders')->nullOnDelete()->cascadeOnUpdate();
        });

        // 8. ORDER STATUS HISTORY
        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('status', [
                'pending', 'out_for_pickup', 'received', 'washing', 'rinsing', 'drying',
                'finish', 'out_for_delivery', 'completed', 'cancelled',
            ]);
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 9. QR CODES
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('qr_token', 255)->unique();
            $table->string('qr_image', 255)->nullable();
            $table->enum('status', ['active', 'used', 'expired', 'cancelled'])->default('active');
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });

        // 10. QR SCAN LOGS
        Schema::create('qr_scan_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_code_id')->constrained('qr_codes')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('scanned_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->enum('scan_type', ['customer_scan', 'staff_scan', 'pickup_scan', 'delivery_scan', 'other'])->default('staff_scan');
            $table->string('device', 255)->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 12. PAYMENTS
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('payment_reference', 100)->nullable()->unique();
            $table->enum('payment_method', ['cash', 'gcash', 'maya', 'bank_transfer', 'qr_payment', 'other'])->default('cash');
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded', 'cancelled'])->default('pending');
            $table->dateTime('paid_at')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 13. PICKUP / DELIVERY
        Schema::create('pickup_delivery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('type', ['pickup', 'delivery', 'pickup_delivery']);
            $table->text('address');
            $table->string('contact_number', 20)->nullable();
            $table->date('scheduled_date')->nullable();
            $table->time('scheduled_time')->nullable();
            $table->string('rider_name', 100)->nullable();
            $table->string('rider_phone', 20)->nullable();
            $table->enum('status', ['requested', 'scheduled', 'on_the_way', 'picked_up', 'delivering', 'delivered', 'cancelled'])->default('requested');
            $table->dateTime('picked_up_at')->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 14. PROMOTIONS
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('discount_value', 10, 2)->default(0.00);
            $table->decimal('minimum_amount', 10, 2)->default(0.00);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->unsignedInteger('usage_limit')->nullable();
            $table->unsignedInteger('usage_count')->default(0);
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->timestamps();
        });

        // 15. PROMOTION USAGE
        Schema::create('promotion_usage', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promotion_id')->constrained('promotions')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete()->cascadeOnUpdate();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->decimal('discount_amount', 10, 2)->default(0.00);
            $table->timestamp('used_at')->useCurrent();

            $table->unique(['promotion_id', 'order_id']);
        });

        // 16. NOTIFICATIONS
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title', 255);
            $table->text('message');
            $table->enum('type', ['order', 'payment', 'pickup', 'delivery', 'machine', 'promotion', 'system', 'other'])->default('system');
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->boolean('is_read')->default(false);
            $table->dateTime('read_at')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index('is_read');
        });

        // 17. LOYALTY TRANSACTIONS
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete()->cascadeOnUpdate();
            $table->integer('points');
            $table->enum('transaction_type', ['earned', 'redeemed', 'adjustment', 'expired']);
            $table->string('description', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        // 18. INVENTORY ITEMS
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('category', 100)->nullable();
            $table->string('unit', 30)->default('piece');
            $table->decimal('quantity', 10, 2)->default(0.00);
            $table->decimal('minimum_stock', 10, 2)->default(0.00);
            $table->decimal('unit_cost', 10, 2)->default(0.00);
            $table->enum('status', ['in_stock', 'low_stock', 'out_of_stock', 'inactive'])->default('in_stock');
            $table->timestamps();
        });

        // 19. INVENTORY TRANSACTIONS
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_item_id')->constrained('inventory_items')->cascadeOnDelete()->cascadeOnUpdate();
            $table->enum('type', ['stock_in', 'stock_out', 'adjustment']);
            $table->decimal('quantity', 10, 2);
            $table->string('reason', 255)->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamp('created_at')->useCurrent();
        });

        // 23. CUSTOMER FEEDBACKS & RATINGS
        Schema::create('customer_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('comment');
            $table->enum('status', ['published', 'pending', 'hidden'])->default('published');
            $table->timestamps();
        });

        // 24. SMS PHONE NOTIFICATIONS LOG
        Schema::create('sms_notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                ->nullable()
                ->constrained('orders')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('phone', 30);
            $table->text('message');

            $table->enum('status', [
                'sent',
                'failed',
                'queued',
            ])->default('sent');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
        Schema::dropIfExists('inventory_items');
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('promotion_usage');
        Schema::dropIfExists('promotions');
        Schema::dropIfExists('pickup_delivery');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('qr_scan_logs');
        Schema::dropIfExists('qr_codes');
        Schema::dropIfExists('order_status_history');

        Schema::table('machines', function (Blueprint $table) {
            $table->dropForeign(['current_order_id']);
        });

        Schema::dropIfExists('orders');
        Schema::dropIfExists('machines');
        Schema::dropIfExists('services');
        Schema::dropIfExists('staff_profiles');
        Schema::dropIfExists('customer_profiles');
    }
};
