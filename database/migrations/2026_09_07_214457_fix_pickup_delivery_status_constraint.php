<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * All valid pickup_delivery status values used by the application.
     *
     * @var string[]
     */
    private array $statuses = [
        'requested',
        'scheduled',
        'heading_to_pickup',
        'on_the_way',
        'picked_up',
        'in_shop',
        'delivering',
        'out_for_delivery',
        'delivered',
        'cancelled',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            $list = implode("', '", $this->statuses);

            DB::statement('ALTER TABLE pickup_delivery DROP CONSTRAINT IF EXISTS pickup_delivery_status_check');
            DB::statement("ALTER TABLE pickup_delivery ADD CONSTRAINT pickup_delivery_status_check CHECK (status IN ('{$list}'))");

        } elseif ($driver === 'mysql') {
            $list = implode("', '", $this->statuses);

            try {
                DB::statement("ALTER TABLE pickup_delivery MODIFY COLUMN status ENUM('{$list}') NOT NULL DEFAULT 'requested'");
            } catch (Throwable $e) {
                // Column may already be varchar; ignore
            }
        }
        // SQLite: no check constraints to update
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'pgsql') {
            $original = [
                'requested', 'scheduled', 'on_the_way',
                'picked_up', 'delivering', 'delivered', 'cancelled',
            ];
            $list = implode("', '", $original);

            DB::statement('ALTER TABLE pickup_delivery DROP CONSTRAINT IF EXISTS pickup_delivery_status_check');
            DB::statement("ALTER TABLE pickup_delivery ADD CONSTRAINT pickup_delivery_status_check CHECK (status IN ('{$list}'))");
        }
    }
};
