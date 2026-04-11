<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_coins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Current referral coin balance
            $table->decimal('balance', 15, 2)->default(0);
            // Total earned from referrals
            $table->decimal('total_earned', 15, 2)->default(0);
            // Total converted to normal coins
            $table->decimal('total_converted', 15, 2)->default(0);
            // Total withdrawn as cash
            $table->decimal('total_withdrawn', 15, 2)->default(0);
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_coins');
    }
};
