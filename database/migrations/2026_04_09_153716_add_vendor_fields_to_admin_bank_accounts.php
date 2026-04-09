<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_bank_accounts', function (Blueprint $table) {
            // Link to user (vendor)
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            
            // Vendor profile fields
            $table->string('display_name')->nullable()->after('account_name');
            $table->text('vendor_info')->nullable()->after('display_name');
            $table->text('vendor_notes')->nullable()->after('vendor_info');
            $table->decimal('rate', 8, 2)->default(1.00)->after('vendor_notes'); // Rate per coin (default ₦1.00)
            $table->integer('min_limit')->default(100)->after('rate');
            $table->integer('max_limit')->default(100000)->after('min_limit');
            $table->integer('completion_rate')->default(0)->after('max_limit'); // Percentage
            $table->integer('avg_response_time')->default(20)->after('completion_rate'); // Minutes
            $table->integer('total_sales')->default(0)->after('avg_response_time');
            $table->decimal('rating', 2, 1)->default(0)->after('total_sales'); // 0-5 stars
        });
    }

    public function down(): void
    {
        Schema::table('admin_bank_accounts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'display_name',
                'vendor_info',
                'vendor_notes',
                'rate',
                'min_limit',
                'max_limit',
                'completion_rate',
                'avg_response_time',
                'total_sales',
                'rating',
            ]);
        });
    }
};
