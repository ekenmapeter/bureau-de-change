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

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route(Auth::user()->role . '.rates.update') }}">
                @csrf
                <div class="space-y-6">
                    @foreach($currencies as $currency)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
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
