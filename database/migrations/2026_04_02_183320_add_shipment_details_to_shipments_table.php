<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->string('courier')->nullable();
            $table->integer('quantity')->nullable();
            $table->boolean('is_fragile')->nullable()->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('shipments', function (Blueprint $table) {
            $table->dropColumn(['courier', 'quantity', 'is_fragile']);
        });
    }
};