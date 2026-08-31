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
        Schema::table('pickup_delivery', function (Blueprint $table) {
            if (! Schema::hasColumn('pickup_delivery', 'pickup_proof_image')) {
                $table->string('pickup_proof_image')->nullable()->after('notes');
            }
            if (! Schema::hasColumn('pickup_delivery', 'delivery_proof_image')) {
                $table->string('delivery_proof_image')->nullable()->after('pickup_proof_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pickup_delivery', function (Blueprint $table) {
            if (Schema::hasColumn('pickup_delivery', 'pickup_proof_image')) {
                $table->dropColumn('pickup_proof_image');
            }
            if (Schema::hasColumn('pickup_delivery', 'delivery_proof_image')) {
                $table->dropColumn('delivery_proof_image');
            }
        });
    }
};
