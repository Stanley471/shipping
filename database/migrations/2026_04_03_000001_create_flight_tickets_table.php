<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flight_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Ticket info
            $table->string('ticket_number')->unique();
            $table->string('booking_reference')->unique();
            $table->string('passenger_name');
            
            // Flight details
            $table->string('flight_number');
            $table->string('airline');
            $table->string('origin');
            $table->string('destination');
            $table->date('flight_date');
            $table->time('departure_time');
            $table->time('arrival_time');
            $table->string('seat');
            $table->string('gate');
            $table->string('class');
            
            // Metadata
            $table->string('pdf_path')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamp('last_downloaded_at')->nullable();
            
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('booking_reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flight_tickets');
    }
};