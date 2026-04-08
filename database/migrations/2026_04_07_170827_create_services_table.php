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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // e.g., 'flight_ticket'
            $table->string('name'); // Display name
            $table->text('description')->nullable();
            $table->boolean('is_free')->default(false); // If true, costs 0 coins
            $table->integer('coin_cost')->default(0); // Coin cost if not free
            $table->boolean('is_active')->default(true); // Can be disabled
            $table->json('settings')->nullable(); // Extra service-specific settings
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
