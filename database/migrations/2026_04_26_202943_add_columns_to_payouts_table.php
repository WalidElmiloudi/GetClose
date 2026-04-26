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
        // Migration to enhance payouts table
        Schema::table('payouts', function (Blueprint $table) {
            $table->foreignId('shop_id')->nullable()->constrained('shops')->nullOnDelete()->after('vendor_id');
            $table->string('transaction_reference')->nullable()->after('amount');
            $table->text('notes')->nullable()->after('status');
            $table->decimal('processing_fee', 12, 2)->default(0)->after('amount');
            $table->decimal('net_amount', 12, 2)->after('amount');  // amount - processing_fee
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            //
        });
    }
};
