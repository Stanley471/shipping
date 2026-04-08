<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('coin_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('amount_coins'); // Coins user wants to buy
            $table->integer('amount_naira'); // NGN amount to pay (1:1)
            $table->string('bank_name'); // Bank to pay to
            $table->string('account_number'); // Admin account number
            $table->string('account_name'); // Admin account name
            $table->string('proof_image')->nullable(); // Payment proof screenshot
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable(); // Reason for rejection or approval note
            $table->foreignId('processed_by')->nullable()->constrained('users'); // Admin who processed
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->index(['status', 'created_at']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coin_purchases');
    }
};
