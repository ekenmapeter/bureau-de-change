@extends('layouts.app')

@section('title', 'Manage Exchange Rates')

@section('content')
    <h2 class="text-2xl font-bold mb-6">Manage Exchange Rates</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="mb-6 flex justify-end">
        <a href="{{ route('manager.currencies.create') }}" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
            Add New Currency
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route(Auth::user()->role . '.rates.update') }}">
                @csrf
                <div class="space-y-6">
                    @foreach($currencies as $currency)
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-center">
                            <div class="md:col-span-1">
                                <label class="font-semibold text-gray-700">{{ $currency->name }} ({{ $currency->code }})</label>
                            </div>
                            <div class="grid grid-cols-2 gap-4 md:col-span-2">
                                <div>
                                    <label for="buy_rate_{{ $currency->id }}" class="block text-sm font-medium text-gray-500">Buy Rate</label>
                                    <input type="number" step="0.01" name="rates[{{ $currency->id }}][buy_rate]" id="buy_rate_{{ $currency->id }}" value="{{ $currency->rates->first()->buy_rate ?? '0.00' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label for="sell_rate_{{ $currency->id }}" class="block text-sm font-medium text-gray-500">Sell Rate</label>
                                    <input type="number" step="0.01" name="rates[{{ $currency->id }}][sell_rate]" id="sell_rate_{{ $currency->id }}" value="{{ $currency->rates->first()->sell_rate ?? '0.00' }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                            </div>
                            <div class="md:col-span-1 flex justify-end">
                                <form action="{{ route('manager.currencies.destroy', $currency->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this currency? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end mt-6">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Update Rates
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
