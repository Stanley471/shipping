<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('User earning the referral coins');
            $table->foreignId('referred_user_id')->nullable()->constrained('users')->nullOnDelete()->comment('The user who was referred');
            
            // Transaction type
            $table->enum('type', [
                'signup_bonus',        // Fixed bonus when someone signs up
                'purchase_commission', // Percentage of referred user's purchase
                'converted_to_coins',  // Converted to normal coins
                'withdrawal',          // Withdrawn as cash
                'admin_adjustment',    // Admin manual adjustment
            ]);
            
            // Amount (positive for earnings, negative for withdrawals/conversions)
            $table->decimal('amount', 15, 2);
            
            // Current balance after transaction
            $table->decimal('balance_after', 15, 2);
            
            // Description
            $table->text('description')->nullable();
            
            // For purchase commissions - track the original purchase
            $table->foreignId('coin_purchase_id')->nullable()->constrained()->nullOnDelete();
            
            // For conversions - track the coin transaction created
            $table->foreignId('coin_transaction_id')->nullable()->constrained('coin_transactions')->nullOnDelete();
            
            // Status (for withdrawals)
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('completed');
            
            // Metadata (bank details for withdrawal, etc.)
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Indexes for reporting
            $table->index(['user_id', 'type']);
            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_transactions');
    }
};
