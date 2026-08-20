<?php

namespace Database\Seeders;

use App\Models\Machine;
use Illuminate\Database\Seeder;

class CleanMachineFleetSeeder extends Seeder
{
    public function run(): void
    {
        Machine::query()->delete();

        for ($i = 1; $i <= 20; $i++) {
            $code = 'WD-'.str_pad($i, 3, '0', STR_PAD_LEFT);
            Machine::create([
                'machine_code' => $code,
                'machine_name' => "Machine {$i}",
                'machine_type' => 'washer_dryer',
                'status' => 'idle',
            ]);
        }
    }
}
