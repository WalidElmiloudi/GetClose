@extends('layouts.app')
@section('page', 'FINANCIALS')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">Financial Dashboard</h1>
            <p class="text-lg text-gray-600">Track your earnings, payouts, and pending funds</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Balance Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Available Balance -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase">Available to Withdraw</h3>
                    <i class="ph-fill ph-wallet text-3xl text-green-500"></i>
                </div>
                <p class="text-3xl font-bold text-gray-800">${{ number_format($balance->available_balance ?? 0, 2) }}</p>
                @if(($balance->available_balance ?? 0) > 0)
                    <button onclick="document.getElementById('withdraw-modal').classList.remove('hidden')" 
                        class="mt-4 w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 rounded-lg transition-colors">
                        Withdraw Funds
                    </button>
                @else
                    <p class="mt-4 text-sm text-gray-500">No funds available for withdrawal</p>
                @endif
            </div>

            <!-- Pending Balance -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase">Pending (30-Day Hold)</h3>
                    <i class="ph-fill ph-clock text-3xl text-yellow-500"></i>
                </div>
                <p class="text-3xl font-bold text-gray-800">${{ number_format($balance->pending_balance ?? 0, 2) }}</p>
                <p class="mt-2 text-sm text-gray-500">Funds will become available soon</p>
            </div>

            <!-- Total Balance -->
            <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-500 uppercase">Total Balance</h3>
                    <i class="ph-fill ph-trend-up text-3xl text-blue-500"></i>
                </div>
                <p class="text-3xl font-bold text-gray-800">${{ number_format($balance->current_balance ?? 0, 2) }}</p>
                <div class="mt-2 text-xs text-gray-500">
                    <p>Earned: ${{ number_format($balance->total_earnings ?? 0, 2) }}</p>
                    <p>Refunded: ${{ number_format($balance->total_refunds ?? 0, 2) }}</p>
                    <p>Withdrawn: ${{ number_format($balance->total_withdrawn ?? 0, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Upcoming Availability -->
        @if(count($eligibility['pending_details']) > 0)
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">When Your Funds Become Available</h2>
            <div class="space-y-3">
                @foreach($eligibility['pending_details'] as $pending)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-semibold text-gray-800">Order #{{ $pending['order_id'] }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $pending['days_until_available'] }} days remaining
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-gray-800">${{ number_format($pending['amount'], 2) }}</p>
                            <p class="text-xs text-gray-500">{{ $pending['available_date']->format('M d, Y') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Quick Links -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <a href="{{ route('vendor.financials.ledger') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="ph-fill ph-list-dashes text-2xl text-purple-500"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Transaction Ledger</h3>
                        <p class="text-sm text-gray-500">View complete transaction history</p>
                    </div>
                </div>
            </a>

            <a href="{{ route('vendor.payouts') }}" class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="ph-fill ph-bank text-2xl text-blue-500"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Payout History</h3>
                        <p class="text-sm text-gray-500">View your withdrawal history</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-2xl font-bold text-gray-800">Recent Transactions</h2>
                    <a href="{{ route('vendor.financials.ledger') }}" class="text-red-500 hover:text-red-700 font-semibold">
                        View All →
                    </a>
                </div>
            </div>
            
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($recentLedger as $transaction)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $transaction->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($transaction->type === 'order_payment')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        <i class="ph-fill ph-arrow-down"></i> Payment
                                    </span>
                                @elseif($transaction->type === 'full_refund')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                        <i class="ph-fill ph-arrow-up"></i> Full Refund
                                    </span>
                                @elseif($transaction->type === 'partial_refund')
                                    <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">
                                        <i class="ph-fill ph-arrow-up"></i> Partial Refund
                                    </span>
                                @elseif($transaction->type === 'payout_withdrawal')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                        <i class="ph-fill ph-arrow-up"></i> Withdrawal
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                {{ $transaction->description }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->amount > 0 ? '+' : '' }}${{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($transaction->type === 'order_payment')
                                    @if($transaction->is_available)
                                        <span class="text-green-600 text-xs font-semibold">
                                            <i class="ph-fill ph-check-circle"></i> Available
                                        </span>
                                    @else
                                        <span class="text-yellow-600 text-xs font-semibold">
                                            <i class="ph-fill ph-clock"></i> Pending
                                        </span>
                                    @endif
                                @else
                                    <span class="text-gray-500 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <i class="ph-fill ph-receipt text-6xl mb-4 block text-gray-300"></i>
                                <p class="text-lg">No transactions yet</p>
                                <p class="text-sm">Transactions will appear here when you receive orders</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Withdrawal Modal -->
    @if(($balance->available_balance ?? 0) > 0)
    <div id="withdraw-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-2xl font-bold text-gray-800">Request Withdrawal</h3>
                    <button onclick="document.getElementById('withdraw-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                        <i class="ph-bold ph-x text-2xl"></i>
                    </button>
                </div>
            </div>

            <form action="{{ route('vendor.payouts.request') }}" method="POST" class="p-6">
                @csrf
                
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Withdrawal Amount</label>
                    <input type="number" name="amount" step="0.01" min="10" max="{{ $balance->available_balance }}" 
                        required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500"
                        placeholder="0.00">
                    <p class="text-sm text-gray-500 mt-2">
                        Available: ${{ number_format($balance->available_balance, 2) }} | Minimum: $10.00
                    </p>
                    @error('amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <p class="text-sm text-blue-800">
                        <i class="ph-fill ph-info"></i> 
                        Withdrawals are typically processed within 1-2 business days.
                    </p>
                </div>

                <div class="flex gap-4">
                    <button type="button" onclick="document.getElementById('withdraw-modal').classList.add('hidden')" 
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-lg transition-colors">
                        Request Withdrawal
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</main>
@endsection
