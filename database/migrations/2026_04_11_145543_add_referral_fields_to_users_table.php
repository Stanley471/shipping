<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Unique referral code for each user
            $table->string('referral_code', 20)->unique()->nullable()->after('id');
            // Who referred this user
            $table->foreignId('referred_by')->nullable()->constrained('users')->nullOnDelete()->after('referral_code');
            // Track total successful referrals
            $table->integer('total_referrals')->default(0)->after('referred_by');
            // Track referral earnings (in referral coins)
            $table->decimal('total_referral_earnings', 15, 2)->default(0)->after('total_referrals');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropColumn(['referral_code', 'referred_by', 'total_referrals', 'total_referral_earnings']);
        });
    }
};
