<?php

namespace Database\Seeders;

use App\Models\ReferralSetting;
use Illuminate\Database\Seeder;

class ReferralSettingsSeeder extends Seeder
{
    public function run(): void
    {
        ReferralSetting::firstOrCreate(
            [],
            [
                'signup_bonus_amount' => 50,
                'purchase_commission_percent' => 10.00,
                'min_withdrawal_amount' => 1000,
                'conversion_rate' => 1.00,
                'is_active' => true,
            ]
        );

        $this->command->info('Referral settings created successfully.');
    }
}
