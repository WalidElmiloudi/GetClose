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
        Schema::create('vendor_ledger', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('payout_id')->nullable()->constrained('payouts')->nullOnDelete();
            $table->foreignId('refund_id')->nullable()->constrained('refunds')->nullOnDelete();

            $table->enum('type', [
                'order_payment',      // Money in from order
                'full_refund',        // Money out from full refund
                'partial_refund',     // Money out from partial refund
                'payout_withdrawal',  // Vendor withdrew funds
                'payout_failed',      // Withdrawal failed, returned to balance
                'admin_adjustment'    // Manual admin correction
            ]);

            $table->decimal('amount', 12, 2);  // Positive for earnings, negative for refunds/withdrawals
            $table->decimal('running_balance', 12, 2);  // Balance after this transaction

            $table->timestamp('payment_date')->nullable();  // When original payment occurred
            $table->timestamp('available_date')->nullable();  // When funds become available (payment_date + 30 days)
            $table->boolean('is_available')->default(false);  // Can be withdrawn

            $table->text('description')->nullable();  // Human-readable explanation
            $table->json('metadata')->nullable();  // Extra context (order items, refund reason, etc.)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_ledger');
    }
};
