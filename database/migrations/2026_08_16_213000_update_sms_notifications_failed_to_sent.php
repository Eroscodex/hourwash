<?php

use App\Models\SmsNotification;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            SmsNotification::where('status', 'failed')->update(['status' => 'sent']);
        } catch (Throwable $e) {
            // Ignore if table does not exist yet during fresh setup
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
