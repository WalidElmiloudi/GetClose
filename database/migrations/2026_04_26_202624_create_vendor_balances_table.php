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
        Schema::create('vendor_balances', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('shop_id')->unique()->constrained('shops')->cascadeOnDelete();
            $table->decimal('total_earnings', 12, 2)->default(0);      // All-time earnings
            $table->decimal('total_refunds', 12, 2)->default(0);       // All-time refunds
            $table->decimal('current_balance', 12, 2)->default(0);     // Available + pending
            $table->decimal('available_balance', 12, 2)->default(0);   // Can withdraw now
            $table->decimal('pending_balance', 12, 2)->default(0);     // In 30-day hold
            $table->decimal('total_withdrawn', 12, 2)->default(0);     // Lifetime withdrawals
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_balances');
    }
};
