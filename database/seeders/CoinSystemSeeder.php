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
                'coin_cost' => 100, // 100 Naira per ticket
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
