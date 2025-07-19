@extends('layouts.app')

@section('title', 'Cash Deposits')

@section('content')
<!-- Stats Section -->
<div class="mb-2">
    <h2 class="text-xl font-bold mb-4">Deposit Statistics</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Today's Deposits -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h4 class="text-gray-500 text-sm font-medium">DEPOSITS TODAY</h4>
            <p class="text-lg font-bold">₦{{ number_format($totalDepositsToday, 2) }}</p>
            <p class="text-gray-500 text-sm">{{ $countDepositsToday }} transaction(s)</p>
        </div>
        <!-- All-Time Deposits -->
        <div class="bg-white p-4 rounded-lg shadow">
            <h4 class="text-gray-500 text-sm font-medium">ALL-TIME DEPOSITS</h4>
            <p class="text-lg font-bold">₦{{ number_format($totalDepositsAllTime, 2) }}</p>
        </div>
        <!-- Deposits by Currency -->
        <div class="lg:col-span-2 bg-white p-4 rounded-lg shadow">
            <h4 class="text-gray-500 text-sm font-medium mb-2">DEPOSITS BY CURRENCY</h4>
            <div class="space-y-2">
                @forelse($depositsByCurrency as $stat)
                    <div class="flex justify-between items-center text-sm">
                        <span class="font-medium">{{ $stat->wallet->currency->name }} ({{$stat->wallet->currency->code}})</span>
                        <span class="font-bold">{{ number_format($stat->total_amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-gray-500 text-sm">No deposits recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 @if(auth()->user()->isAdmin() || auth()->user()->isManager()) lg:grid-cols-3 @endif gap-8">
    <!-- Deposit Form -->
    <div class="lg:col-span-1">
        <div class="bg-white p-2 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4">Record New Deposit</h3>
            <form action="{{ route('admin.cash-deposits.store') }}" method="POST">
                @csrf
                <div class="space-y-2">
                    <div>
                        <label for="wallet_id" class="block text-sm font-medium text-gray-700">Currency</label>
                        <select id="wallet_id" name="wallet_id" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md" required>
                            @foreach($wallets as $wallet)
                                <option value="{{ $wallet->id }}">{{ $wallet->currency->name }} ({{ $wallet->currency->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                        <input type="number" id="amount" name="amount" step="0.01" min="0" class=" block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" required>
                        <small id="amount_in_words" class="text-gray-500"></small>
                    </div>
                    <div>
                        <label for="depositor_name" class="block text-sm font-medium text-gray-700">Depositor Name (Optional)</label>
                        <input type="text" id="depositor_name" name="depositor_name" class=" block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="depositor_phone" class="block text-sm font-medium text-gray-700">Depositor Phone (Optional)</label>
                        <input type="text" id="depositor_phone" name="depositor_phone" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="depositor_email" class="block text-sm font-medium text-gray-700">Depositor Email (Optional)</label>
                        <input type="email" id="depositor_email" name="depositor_email" class=" block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="description" name="description" rows="3" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700">Submit Deposit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Deposit History -->
    @if(auth()->user()->isAdmin() || auth()->user()->isManager())
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4">Deposit History</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Currency</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($deposits as $deposit)
                                <tr>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $deposit->created_at->format('d M, Y h:ia') }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $deposit->user->name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $deposit->wallet->currency->code }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">{{ number_format($deposit->amount, 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                                        <button class="text-indigo-600 hover:text-indigo-900 view-details-btn"
                                                data-deposit='@json($deposit)'>
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">No deposits found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $deposits->links() }}
                </div>
            </div>
        </div>

        <!-- Details Modal -->
        <x-modal name="deposit-details" :show="false" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900" id="modal_title">
                    Deposit Details
                </h2>

                <div class="mt-4 space-y-2 text-sm text-gray-600" id="modal_body">
                    <!-- Deposit details will be injected here by JavaScript -->
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Close') }}
                    </x-secondary-button>
                </div>
            </div>
        </x-modal>
    @endif
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/number-to-words@1.2.4/numberToWords.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Amount to words script
        const amountInput = document.getElementById('amount');
        if (amountInput) {
            amountInput.addEventListener('input', function(e) {
                let amount = e.target.value;
                let amountInWords = document.getElementById('amount_in_words');
                if (amount) {
                    amountInWords.textContent = toWords(amount);
                } else {
                    amountInWords.textContent = '';
                }
            });
        }

        // Modal script
        const viewButtons = document.querySelectorAll('.view-details-btn');
        const modalBody = document.getElementById('modal_body');

        viewButtons.forEach(button => {
            button.addEventListener('click', () => {
                const deposit = JSON.parse(button.dataset.deposit);

                modalBody.innerHTML = `
                    <p><strong>Date:</strong> ${new Date(deposit.created_at).toLocaleString()}</p>
                    <p><strong>Recorded by:</strong> ${deposit.user.name}</p>
                    <p><strong>Currency:</strong> ${deposit.wallet.currency.name} (${deposit.wallet.currency.code})</p>
                    <p><strong>Amount:</strong> ${parseFloat(deposit.amount).toFixed(2)}</p>
                    <hr class="my-2">
                    <p><strong>Depositor:</strong> ${deposit.depositor_name || 'N/A'}</p>
                    <p><strong>Phone:</strong> ${deposit.depositor_phone || 'N/A'}</p>
                    <p><strong>Email:</strong> ${deposit.depositor_email || 'N/A'}</p>
                    <hr class="my-2">
                    <p><strong>Description:</strong></p>
                    <p>${deposit.description || 'No description provided.'}</p>
                `;

                window.dispatchEvent(new CustomEvent('open-modal', { detail: 'deposit-details' }));
            });
        });

        function toWords(number) {
            const [integerPart, decimalPart] = String(number).split('.');
            let words = numberToWords.toWords(integerPart);
            if (decimalPart) {
                words += ' and ' + toWordsWithCents(decimalPart.padEnd(2, '0'));
            }
            return capitalize(words);
        }

        function toWordsWithCents(number) {
            if (number === '00') {
                return 'zero cents';
            }
            let words = numberToWords.toWords(number);
            return words + ' cents';
        }

        function capitalize(s) {
            return s.charAt(0).toUpperCase() + s.slice(1);
        }
    });
</script>
@endpush
