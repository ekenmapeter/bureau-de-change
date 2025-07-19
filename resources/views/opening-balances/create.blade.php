@extends('layouts.app')

@section('title', 'Add New Opening Balance')
@section('meta_description', 'Record a new opening balance for the day.')

@section('content')
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Record Opening Balances</h1>
                <p class="mt-1 text-sm text-gray-600">Record the starting cash balance for each wallet for the day.</p>
            </div>
        </div>

        <!-- Info Message -->
        @if (session('info'))
            <div class="rounded-md bg-blue-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-blue-800">{{ session('info') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="rounded-md bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul role="list" class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Create Form -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <form action="{{ route('opening-balances.store') }}" method="POST">
                @csrf
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($wallets as $wallet)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                <label for="amount_{{ $wallet->id }}" class="block text-sm font-medium text-gray-700">{{ $wallet->currency->name }} ({{ $wallet->currency->code }})</label>
                                <div>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">{{ $wallet->currency->symbol ?? '₦' }}</span>
                                        </div>
                                        <input type="number" id="amount_{{ $wallet->id }}" name="amounts[{{ $wallet->id }}]" value="{{ old('amounts.'.$wallet->id, 0) }}" step="0.01" min="0" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md amount-input" placeholder="0.00" data-target-id="amount-in-words-{{ $wallet->id }}">
                                    </div>
                                    <div id="amount-in-words-{{ $wallet->id }}" class="mt-1 text-sm text-gray-500"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Form Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Submit Opening Balances
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/number-to-words@1.2.4/dist/numberToWords.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const amountInputs = document.querySelectorAll('.amount-input');

            function toWords(number) {
                if (typeof numberToWords === 'undefined' || !numberToWords.toWords) {
                    return ''; // Library not loaded
                }

                const [integerPart, decimalPartRaw] = String(number).split('.');
                let words = numberToWords.toWords(parseInt(integerPart) || 0);

                words = capitalize(words) + ' Naira';

                if (decimalPartRaw && parseInt(decimalPartRaw) > 0) {
                    const koboPart = (decimalPartRaw + '00').substring(0, 2);
                    const koboWords = numberToWords.toWords(parseInt(koboPart));
                    words += ' and ' + koboWords + ' kobo';
                }

                return words + ' only.';
            }

            function capitalize(s) {
                if (typeof s !== 'string' || s.length === 0) return '';
                return s.charAt(0).toUpperCase() + s.slice(1);
            }

            amountInputs.forEach(input => {
                // Function to update words
                const updateWords = () => {
                    const targetId = input.dataset.targetId;
                    const targetDiv = document.getElementById(targetId);
                    const value = parseFloat(input.value);

                    if (!isNaN(value)) {
                        targetDiv.textContent = toWords(value);
                    } else {
                        targetDiv.textContent = '';
                    }
                };

                input.addEventListener('input', updateWords);

                // Initial call to set words for pre-filled values
                updateWords();
            });
        });
    </script>
@endsection
