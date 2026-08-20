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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('stamps_count')->default(0)->after('status');
            $table->unsignedInteger('completed_cards_count')->default(0)->after('stamps_count');
            $table->unsignedInteger('discount_rewards_available')->default(0)->after('completed_cards_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['stamps_count', 'completed_cards_count', 'discount_rewards_available']);
        });
    }
};
