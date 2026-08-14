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
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('machine_status_logs');
        Schema::dropIfExists('maintenance_records');
        Schema::dropIfExists('announcements');
        Schema::dropIfExists('activity_logs');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate tables if rolled back
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('service_id')->nullable()->constrained('services')->nullOnDelete()->cascadeOnUpdate();
            $table->string('description', 255)->nullable();
            $table->decimal('quantity', 8, 2)->default(1);
            $table->decimal('weight_kg', 8, 2)->default(0);
            $table->decimal('unit_price', 8, 2)->default(0);
            $table->decimal('subtotal', 8, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('machine_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('previous_status', 50);
            $table->string('new_status', 50);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('machine_id')->constrained('machines')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('reported_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('maintenance_type', 100);
            $table->text('description')->nullable();
            $table->decimal('cost', 8, 2)->default(0);
            $table->enum('status', ['reported', 'in_progress', 'completed', 'cancelled'])->default('reported');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });

        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->text('content');
            $table->string('image', 255)->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->string('action', 255);
            $table->string('module', 100)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }
};
