@extends('layouts.app')

@section('title', 'New Transaction')

@section('content')
    <h2 class="text-2xl font-bold mb-6">
        {{ __('New Transaction') }}
    </h2>

    <div class="max-w-4xl mx-auto">
        <!-- Wallet Balances -->


        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route($prefix . '.transactions.store') }}">
                    @csrf

                    <!-- Transaction Type -->
                    <div>
                        <label for="type" class="block font-medium text-sm text-gray-700">{{ __('Transaction Type') }}</label>
                        <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="purchase">Purchase (Buy from Customer)</option>
                            <option value="sale">Sale (Sell to Customer)</option>
                        </select>
                    </div>

                    <!-- Currency -->
                    <div class="mt-4">
                        <label for="currency_id" class="block font-medium text-sm text-gray-700">{{ __('Currency') }}</label>
                        <select id="currency_id" name="currency_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            @foreach($transactionCurrencies as $currency)
                                @if($currency->rates->isNotEmpty())
                                    <option value="{{ $currency->id }}" data-buy-rate="{{ $currency->rates->first()->buy_rate ?? 0 }}" data-sell-rate="{{ $currency->rates->first()->sell_rate ?? 0 }}">{{ $currency->name }} ({{ $currency->code }})</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <!-- Rate -->
                    <div class="mt-4">
                        <label for="rate" class="block font-medium text-sm text-gray-700">{{ __('Exchange Rate (NGN)') }}</label>
                        <input id="rate" name="rate" type="number" step="0.01" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                    </div>

                    <!-- Quantity -->
                    <div class="mt-4">
                        <label for="quantity" class="block font-medium text-sm text-gray-700">{{ __('Foreign Currency Quantity') }}</label>
                        <input id="quantity" name="quantity" type="number" step="0.01" required class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" />
                    </div>

                    <!-- Total NGN Amount -->
                    <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                         <h3 class="text-lg font-medium text-gray-900">Total NGN Amount: <span id="total_amount" class="font-bold">0.00</span></h3>
                         <input type="hidden" name="amount_in_ngn" id="amount_in_ngn" value="0">
                         <input type="hidden" name="amount_foreign" id="amount_foreign" value="0">
                         <small id="total_amount_in_words" class="text-gray-500"></small>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Record Transaction') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/number-to-words@1.2.4/dist/numberToWords.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const typeSelect = document.getElementById('type');
        const currencySelect = document.getElementById('currency_id');
        const rateInput = document.getElementById('rate');
        const quantityInput = document.getElementById('quantity');
        const totalAmountSpan = document.getElementById('total_amount');
        const totalAmountInWords = document.getElementById('total_amount_in_words');
        const amountNgnInput = document.getElementById('amount_in_ngn');
        const amountForeignInput = document.getElementById('amount_foreign');
        const ngnBalanceEl = document.getElementById('ngn-balance');
        const foreignBalanceEl = document.getElementById('foreign-balance');
        const foreignCurrencyNameEl = document.getElementById('foreign-currency-name');
        let walletBalances = {};
        let ngnCurrencyId = null;

        function updateRate() {
            const selectedOption = currencySelect.options[currencySelect.selectedIndex];
            if (!selectedOption) return;

            const type = typeSelect.value;

            if (type === 'purchase') {
                rateInput.value = selectedOption.getAttribute('data-buy-rate');
            } else {
                rateInput.value = selectedOption.getAttribute('data-sell-rate');
            }
            updateTotal();
        }

        function updateTotal() {
            const rate = parseFloat(rateInput.value) || 0;
            const quantity = parseFloat(quantityInput.value) || 0;
            const total = rate * quantity;

            totalAmountSpan.textContent = new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN' }).format(total);
            amountNgnInput.value = total.toFixed(2);
            amountForeignInput.value = quantity.toFixed(2);
            totalAmountInWords.textContent = toWords(total);
        }

        function fetchBalances() {
            fetch("{{ route('api.wallets') }}")
                .then(response => response.json())
                .then(data => {
                    walletBalances = data;
                    const ngnCurrency = @json($allCurrencies->firstWhere('code', 'NGN'));
                    ngnCurrencyId = ngnCurrency ? ngnCurrency.id : null;
                    updateBalances();
                });
        }

        function updateBalances() {
            const selectedCurrencyId = currencySelect.value;
            const selectedOption = currencySelect.options[currencySelect.selectedIndex];

            // Update NGN balance
            const ngnBalance = walletBalances[ngnCurrencyId] || 0;
            ngnBalanceEl.textContent = new Intl.NumberFormat('en-NG', { style: 'currency', currency: 'NGN' }).format(ngnBalance);

            // Update Foreign Currency balance
            const foreignBalance = walletBalances[selectedCurrencyId] || 0;
            foreignBalanceEl.textContent = new Intl.NumberFormat('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(foreignBalance);
            foreignCurrencyNameEl.textContent = `${selectedOption.text.split(' ')[0]} Balance`;
        }

        currencySelect.addEventListener('change', updateRate);
        currencySelect.addEventListener('change', updateBalances);
        rateInput.addEventListener('input', updateTotal);
        quantityInput.addEventListener('input', updateTotal);

        updateRate();
        fetchBalances();

        function toWords(number) {
            const [integerPart, decimalPart] = String(number).split('.');
            let words = numberToWords.toWords(integerPart);
            words = capitalize(words) + ' Naira';

            if (decimalPart && parseInt(decimalPart) > 0) {
                const koboPart = decimalPart.padEnd(2, '0');
                const koboWords = numberToWords.toWords(koboPart);
                words += ' and ' + koboWords + ' kobo';
            }

            return words + ' only.';
        }

        function capitalize(s) {
            if (typeof s !== 'string') return ''
            return s.charAt(0).toUpperCase() + s.slice(1)
        }
    });
</script>
@endpush
