<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tracking_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained()->onDelete('cascade');
            $table->string('status');
            $table->string('location')->nullable();
            $table->text('note')->nullable();
            $table->unsignedInteger('progress')->default(0);
            $table->dateTime('occurred_at');
            $table->timestamps();

            // Speed up timeline queries
            $table->index('shipment_id');
            $table->index(['shipment_id', 'occurred_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_updates');
    }
};