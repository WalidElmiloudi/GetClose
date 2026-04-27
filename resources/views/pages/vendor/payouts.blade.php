@extends('layouts.app')
@section('page', 'PAYOUT HISTORY')
@section('content')
<main class="w-full min-h-screen bg-gradient-to-b from-red-50 to-white py-10 px-4 lg:px-30">
    <div class="max-w-7xl mx-auto">
        <div class="mb-10">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl lg:text-5xl font-bold text-red-950 mb-4">Payout History</h1>
                    <p class="text-lg text-gray-600">Track all your withdrawal requests</p>
                </div>
                <a href="{{ route('vendor.financials') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                    <i class="ph-bold ph-arrow-left"></i> Back to Financials
                </a>
            </div>
        </div>

        <!-- Payout List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payout ID</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Fee</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Net Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payouts as $payout)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $payout->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                #{{ $payout->id }}
                            </td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-800">
                                ${{ number_format($payout->amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right text-sm text-gray-500">
                                ${{ number_format($payout->processing_fee, 2) }}
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-gray-800">
                                ${{ number_format($payout->net_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($payout->status === 'pending')
                                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                        <i class="ph-fill ph-clock"></i> Pending
                                    </span>
                                @elseif($payout->status === 'paid')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                        <i class="ph-fill ph-check-circle"></i> Paid
                                    </span>
                                @elseif($payout->status === 'processing')
                                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                        <i class="ph-fill ph-spinner"></i> Processing
                                    </span>
                                @elseif($payout->status === 'failed')
                                    <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                        <i class="ph-fill ph-x-circle"></i> Failed
                                    </span>
                                @elseif($payout->status === 'cancelled')
                                    <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs font-semibold">
                                        <i class="ph-fill ph-x"></i> Cancelled
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $payout->notes ?? '—' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <i class="ph-fill ph-bank text-6xl mb-4 block text-gray-300"></i>
                                <p class="text-lg">No payouts yet</p>
                                <p class="text-sm">Request a withdrawal from your financial dashboard</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $payouts->links() }}
        </div>
    </div>
</main>
@endsection
