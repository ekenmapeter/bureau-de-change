@extends('layouts.app')

@section('title', 'Edit Opening Balance')
@section('meta_description', 'Edit an existing opening balance record.')

@section('content')
    <div class="container-fluid px-4 sm:px-6 lg:px-8 py-6">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Opening Balances</h1>
                <p class="mt-1 text-sm text-gray-600">Update the opening balance records for the day.</p>
            </div>
        </div>

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

        <!-- Edit Form -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            @if($openingBalances->isNotEmpty())
                <form action="{{ route('opening-balances.update', $openingBalances->first()->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($openingBalances as $balance)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                                    <label for="amount_{{ $balance->id }}" class="block text-sm font-medium text-gray-700">{{ $balance->wallet->currency->name }} ({{ $balance->wallet->currency->code }})</label>
                                    <div>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">{{ $balance->wallet->currency->symbol ?? '' }}</span>
                                            </div>
                                            <input type="number" id="amount_{{ $balance->id }}" name="amounts[{{ $balance->id }}]" value="{{ old('amounts.'.$balance->id, $balance->amount) }}" step="0.01" min="0" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md amount-input" placeholder="0.00" data-target-id="amount-in-words-{{ $balance->id }}">
                                        </div>
                                        <div id="amount-in-words-{{ $balance->id }}" class="mt-1 text-sm text-gray-500"></div>
                                    </div>
                                </div>
                            @endforeach
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" name="date" id="date" value="{{ old('date', $openingBalances->first()->date->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- Form Footer -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-3">
                        <a href="{{ route('opening-balances.index') }}" class="inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Update Balance
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/number-to-words@1.2.4/dist/numberToWords.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const amountInput = document.querySelector('.amount-input');

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

            const amountInputs = document.querySelectorAll('.amount-input');

            amountInputs.forEach(input => {
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
                updateWords(); // Initial call
            });
        });
    </script>
@endsection
