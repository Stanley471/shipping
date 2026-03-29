<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tracking_id')->unique();
            $table->string('sender_name');
            $table->string('receiver_name');
            $table->string('receiver_email')->nullable();
            $table->text('pickup_location');
            $table->text('delivery_address');
            $table->dateTime('shipped_at');
            $table->softDeletes();
            $table->timestamps();

            // Speed up lookups
            $table->index('user_id');
            $table->index('tracking_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};