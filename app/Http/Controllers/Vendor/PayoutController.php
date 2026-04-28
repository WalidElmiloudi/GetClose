<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Services\VendorPayoutService;
use App\Models\Payout;
use App\Models\VendorLedger;
use App\Models\VendorBalance;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayoutController extends Controller
{
    protected $payoutService;

    public function __construct(VendorPayoutService $payoutService)
    {
        $this->payoutService = $payoutService;
    }

    /**
     * Financial dashboard
     */
    public function dashboard()
    {
        $shop = auth()->user()->shop;
        if (!$shop) {
            return redirect()->route('vendor.dashboard')
                ->with('error', 'Create a shop first.');
        }

        $balance = VendorBalance::where('shop_id', $shop->id)->first();
        $eligibility = $this->payoutService->getWithdrawalEligibility($shop->id);
        
        $recentLedger = VendorLedger::where('shop_id', $shop->id)
            ->latest()
            ->take(10)
            ->get();

        return view('pages.vendor.financials', compact('balance', 'eligibility', 'recentLedger'));
    }

    /**
     * Full transaction ledger
     */
    public function ledger(): View
    {
        $shop = auth()->user()->shop;
        
        $ledger = VendorLedger::where('shop_id', $shop->id)
            ->with(['order', 'payout'])
            ->latest()
            ->paginate(50);

        return view('pages.vendor.ledger', compact('ledger'));
    }

    /**
     * Check withdrawal eligibility (AJAX)
     */
    public function eligibility()
    {
        $shop = auth()->user()->shop;
        $eligibility = $this->payoutService->getWithdrawalEligibility($shop->id);
        
        return response()->json($eligibility);
    }

    /**
     * List payout history
     */
    public function index(): View
    {
        $shop = auth()->user()->shop;
        
        $payouts = Payout::where('shop_id', $shop->id)
            ->latest()
            ->paginate(20);

        return view('pages.vendor.payouts', compact('payouts'));
    }

    /**
     * Request a payout/withdrawal
     */
    public function request(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10'
        ]);

        $shop = auth()->user()->shop;
        $balance = VendorBalance::where('shop_id', $shop->id)->firstOrFail();

        if ($request->amount > $balance->available_balance) {
            return back()->with('error', 
                'Insufficient available balance. You can withdraw up to $' . 
                number_format($balance->available_balance, 2));
        }

        // Create payout record
        $payout = Payout::create([
            'vendor_id' => auth()->id(),
            'shop_id' => $shop->id,
            'amount' => $request->amount,
            'net_amount' => $request->amount,
            'processing_fee' => 0,
            'status' => 'pending',
            'notes' => 'Withdrawal requested by vendor'
        ]);

        // Create ledger entry
        VendorLedger::create([
            'shop_id' => $shop->id,
            'payout_id' => $payout->id,
            'type' => 'payout_withdrawal',
            'amount' => -$request->amount,
            'description' => "Payout withdrawal #{$payout->id}",
            'is_available' => false
        ]);

        // Update balance
        $balance->decrement('available_balance', $request->amount);
        $balance->increment('total_withdrawn', $request->amount);
        $balance->decrement('current_balance', $request->amount);

        return back()->with('success', 
            "Payout of $" . number_format($request->amount, 2) . " requested successfully.");
    }
}
