<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class OfficialServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Wash Only',
                'description' => 'Individual washing machine cycle per load (max 7kg).',
                'service_type' => 'wash',
                'price' => 75.00,
                'price_unit' => 'load',
                'estimated_minutes' => 35,
                'status' => 'active',
            ],
            [
                'name' => 'Dry Only',
                'description' => 'Individual tumble dryer cycle per load (max 7kg).',
                'service_type' => 'dry',
                'price' => 75.00,
                'price_unit' => 'load',
                'estimated_minutes' => 40,
                'status' => 'active',
            ],
            [
                'name' => 'Fold Only',
                'description' => 'Professional manual folding service per load (max 7kg).',
                'service_type' => 'fold',
                'price' => 50.00,
                'price_unit' => 'load',
                'estimated_minutes' => 15,
                'status' => 'active',
            ],
            [
                'name' => 'Self-Service (Wash & Dry)',
                'description' => 'Self-service machine access for washing and drying per load (max 7kg).',
                'service_type' => 'wash_dry',
                'price' => 150.00,
                'price_unit' => 'load',
                'estimated_minutes' => 75,
                'status' => 'active',
            ],
            [
                'name' => 'Full-Service (Wash, Dry & Fold)',
                'description' => 'Complete drop-off service including washing, drying, and neat folding per load (max 7kg).',
                'service_type' => 'wash_dry_fold',
                'price' => 200.00,
                'price_unit' => 'load',
                'estimated_minutes' => 90,
                'status' => 'active',
            ],
            [
                'name' => 'Full Service with Pickup & Delivery',
                'description' => 'Complete premium door-to-door laundry service with doorstep pickup and delivery per load (max 7kg).',
                'service_type' => 'pickup_delivery',
                'price' => 250.00,
                'price_unit' => 'load',
                'estimated_minutes' => 120,
                'status' => 'active',
            ],
        ];

        foreach ($services as $srv) {
            Service::updateOrCreate(
                ['service_type' => $srv['service_type']],
                $srv
            );
        }
    }
}
