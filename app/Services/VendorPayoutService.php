<?php

namespace App\Services;

use App\Models\Order;
use App\Models\VendorLedger;
use App\Models\VendorBalance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VendorPayoutService
{
    const HOLD_PERIOD_DAYS = 30;

    /**
     * Process order payment and distribute to vendors
     * Called when order status changes to 'paid' or 'completed'
     */
    public function processOrderPayment(Order $order)
    {
        // Prevent double-processing
        if ($order->paid_at) {
            Log::info("Order #{$order->id} already processed for vendor payout");
            return;
        }

        DB::transaction(function () use ($order) {
            // Update order paid_at timestamp
            $order->update(['paid_at' => now()]);

            // Group order items by shop
            $itemsByShop = $order->items->groupBy('product.shop_id');

            foreach ($itemsByShop as $shopId => $items) {
                $shopTotal = $items->sum(function($item) {
                    return $item->price * $item->quantity;
                });

                // Proportionate shipping cost
                $orderItemTotal = $order->items->sum(fn($i) => $i->price * $i->quantity);
                $shopShipping = $orderItemTotal > 0 ? ($shopTotal / $orderItemTotal) * $order->shipping_price : 0;
                
                $totalEarning = $shopTotal + $shopShipping;
                $availableDate = now()->addDays(self::HOLD_PERIOD_DAYS);

                // Create ledger entry
                $ledger = VendorLedger::create([
                    'shop_id' => $shopId,
                    'order_id' => $order->id,
                    'type' => 'order_payment',
                    'amount' => $totalEarning,
                    'payment_date' => now(),
                    'available_date' => $availableDate,
                    'is_available' => false,
                    'description' => "Payment for Order #{$order->id}",
                    'metadata' => [
                        'items' => $items->map(fn($i) => [
                            'product_id' => $i->product_id,
                            'product_name' => $i->product->name,
                            'quantity' => $i->quantity,
                            'price' => $i->price,
                            'subtotal' => $i->price * $i->quantity
                        ]),
                        'shipping_share' => $shopShipping
                    ]
                ]);

                // Update vendor balance
                $this->updateVendorBalance($shopId, $ledger);

                Log::info("Vendor payout processed: Shop {$shopId} earned \${$totalEarning} from Order #{$order->id}");
            }
        });
    }

    /**
     * Process refund (full or partial)
     */
    public function processRefund(Order $order, $refundAmount, $refundType = 'partial', $orderItemId = null)
    {
        DB::transaction(function () use ($order, $refundAmount, $refundType, $orderItemId) {
            // Get affected shops
            if ($refundType === 'full') {
                $itemsByShop = $order->items->groupBy('product.shop_id');
            } else {
                // Partial refund - only affected item's shop
                $item = $order->items()->findOrFail($orderItemId);
                $itemsByShop = collect([$item->product->shop_id => collect([$item])]);
            }

            foreach ($itemsByShop as $shopId => $items) {
                // Calculate shop's refund amount proportionally
                $shopItemsTotal = $items->sum(fn($i) => $i->price * $i->quantity);
                $orderTotal = $order->items->sum(fn($i) => $i->price * $i->quantity);
                $shopRefundAmount = $orderTotal > 0 ? ($shopItemsTotal / $orderTotal) * $refundAmount : 0;

                // Create ledger entry (negative amount)
                $ledger = VendorLedger::create([
                    'shop_id' => $shopId,
                    'order_id' => $order->id,
                    'type' => $refundType === 'full' ? 'full_refund' : 'partial_refund',
                    'amount' => -$shopRefundAmount,  // Negative for money out
                    'payment_date' => $order->paid_at,
                    'available_date' => null,
                    'is_available' => false,
                    'description' => ucfirst($refundType) . " refund for Order #{$order->id}",
                    'metadata' => [
                        'refund_type' => $refundType,
                        'original_payment_date' => $order->paid_at,
                        'refunded_items' => $items->map(fn($i) => [
                            'product_id' => $i->product_id,
                            'refund_amount' => $i->price * $i->quantity
                        ])
                    ]
                ]);

                // Update vendor balance
                $this->updateVendorBalance($shopId, $ledger);

                Log::info("Vendor refund processed: Shop {$shopId} refunded \${$shopRefundAmount} from Order #{$order->id}");
            }

            // Update order refunded amount
            $order->increment('refunded_amount', $refundAmount);
        });
    }

    /**
     * Update vendor balance after ledger entry
     */
    protected function updateVendorBalance($shopId, $ledger)
    {
        $balance = VendorBalance::firstOrCreate(
            ['shop_id' => $shopId],
            [
                'total_earnings' => 0,
                'total_refunds' => 0,
                'current_balance' => 0,
                'available_balance' => 0,
                'pending_balance' => 0,
                'total_withdrawn' => 0
            ]
        );

        if ($ledger->amount > 0) {
            // Earning
            $balance->increment('total_earnings', $ledger->amount);
            $balance->increment('pending_balance', $ledger->amount);
        } else {
            // Refund
            $balance->increment('total_refunds', abs($ledger->amount));
            
            // Deduct from available first, then pending
            if ($balance->available_balance >= abs($ledger->amount)) {
                $balance->decrement('available_balance', abs($ledger->amount));
            } else {
                $remaining = abs($ledger->amount) - $balance->available_balance;
                $balance->update([
                    'available_balance' => 0,
                    'pending_balance' => max(0, $balance->pending_balance - $remaining)
                ]);
            }
        }

        // Recalculate current balance
        $balance->refresh();
        $newCurrentBalance = $balance->total_earnings - $balance->total_refunds - $balance->total_withdrawn;
        
        // Update running balance in ledger
        $ledger->update(['running_balance' => $newCurrentBalance]);
        
        // Update current balance
        $balance->update([
            'current_balance' => $newCurrentBalance
        ]);
    }

    /**
     * Recalculate available balances (run via scheduled job daily)
     */
    public function recalculateAvailableBalances()
    {
        $now = now();

        // Find ledger entries that just became available
        $newlyAvailable = VendorLedger::where('is_available', false)
            ->where('available_date', '<=', $now)
            ->where('type', 'order_payment')
            ->where('amount', '>', 0)
            ->get();

        $count = 0;
        foreach ($newlyAvailable as $ledger) {
            $ledger->update(['is_available' => true]);

            $balance = VendorBalance::where('shop_id', $ledger->shop_id)->first();
            if ($balance) {
                $amount = $ledger->amount;
                $balance->decrement('pending_balance', $amount);
                $balance->increment('available_balance', $amount);
                $count++;
            }
        }

        Log::info("Vendor payout recalculation: {$count} transactions moved to available");
        return $count;
    }

    /**
     * Calculate withdrawal eligibility for a shop
     */
    public function getWithdrawalEligibility($shopId)
    {
        $balance = VendorBalance::where('shop_id', $shopId)->first();
        
        if (!$balance) {
            return [
                'available_balance' => 0,
                'pending_balance' => 0,
                'total_balance' => 0,
                'pending_details' => []
            ];
        }
        
        $pendingTransactions = VendorLedger::where('shop_id', $shopId)
            ->where('is_available', false)
            ->where('type', 'order_payment')
            ->where('amount', '>', 0)
            ->orderBy('available_date')
            ->get();

        return [
            'available_balance' => $balance->available_balance,
            'pending_balance' => $balance->pending_balance,
            'total_balance' => $balance->current_balance,
            'pending_details' => $pendingTransactions->map(function($t) {
                return [
                    'amount' => $t->amount,
                    'available_date' => $t->available_date,
                    'days_until_available' => max(0, now()->diffInDays($t->available_date, false)),
                    'order_id' => $t->order_id
                ];
            })
        ];
    }
}
