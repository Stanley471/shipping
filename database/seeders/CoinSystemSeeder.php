<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;
use App\Models\AdminBankAccount;

class CoinSystemSeeder extends Seeder
{
    public function run(): void
    {
        // Create default services
        $services = [
            [
                'slug' => 'flight_ticket',
                'name' => 'Flight Ticket',
                'description' => 'Generate flight ticket PDF',
                'is_free' => false,
                'coin_cost' => 100,
                'is_active' => true,
            ],
            [
                'slug' => 'create_shipment',
                'name' => 'Create Shipment',
                'description' => 'Create a new shipment/tracking entry',
                'is_free' => false,
                'coin_cost' => 50,
                'is_active' => true,
            ],
            [
                'slug' => 'edit_shipment',
                'name' => 'Edit Shipment',
                'description' => 'Edit shipment details',
                'is_free' => true,
                'coin_cost' => 0,
                'is_active' => true,
            ],
            [
                'slug' => 'update_shipment',
                'name' => 'Update Shipment Status',
                'description' => 'Add tracking updates to shipment',
                'is_free' => true,
                'coin_cost' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(
                ['slug' => $service['slug']],
                $service
            );
        }

        // Create sample admin bank account (for testing)
        if (AdminBankAccount::count() === 0) {
            AdminBankAccount::create([
                'bank_name' => 'First Bank of Nigeria',
                'account_number' => '0123456789',
                'account_name' => 'Shipping App Admin',
                'is_active' => true,
                'sort_order' => 1,
            ]);
        }
    }
}
