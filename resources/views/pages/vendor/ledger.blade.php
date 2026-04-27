@extends('layouts.app')
@section('page', 'TRANSACTION LEDGER')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">Transaction Ledger</h1>
                    <p class="text-lg text-gray-600">Complete history of all your financial transactions</p>
                </div>
                <a href="{{ route('vendor.financials') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    <i class="ph-bold ph-arrow-left"></i> Back to Financials
                </a>
            </div>
        </div>

        <!-- Transaction List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Balance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($ledger as $transaction)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $transaction->created_at->format('M d, Y H:i') }}
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
                                @if($transaction->order_id)
                                    <a href="{{ route('vendor.orders.show', $transaction->order_id) }}" class="text-xs text-red-500 hover:text-red-700 block mt-1">
                                        View Order #{{ $transaction->order_id }}
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right font-bold {{ $transaction->amount > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->amount > 0 ? '+' : '' }}${{ number_format($transaction->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-800">
                                ${{ number_format($transaction->running_balance, 2) }}
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
                                    <span class="text-gray-500 text-xs">Completed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <i class="ph-fill ph-receipt text-6xl mb-4 block text-gray-300"></i>
                                <p class="text-lg">No transactions yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $ledger->links() }}
        </div>
    </div>
</main>
@endsection
