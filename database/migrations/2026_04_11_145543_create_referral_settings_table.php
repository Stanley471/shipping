<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_settings', function (Blueprint $table) {
            $table->id();
            // Fixed amount given when someone signs up with your referral code
            $table->integer('signup_bonus_amount')->default(50)->comment('Coins given per successful referral signup');
            // Percentage of referred user's coin purchases that goes to referrer
            $table->decimal('purchase_commission_percent', 5, 2)->default(10.00)->comment('Percentage of purchase amount');
            // Minimum referral coins required for withdrawal
            $table->integer('min_withdrawal_amount')->default(1000)->comment('Minimum coins to withdraw');
            // Conversion rate: 1 referral coin = X normal coins
            $table->decimal('conversion_rate', 5, 2)->default(1.00)->comment('1 referral coin = X normal coins');
            // Is referral system active
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_settings');
    }
};
