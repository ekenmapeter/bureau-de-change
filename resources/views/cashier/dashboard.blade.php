@extends('layouts.app')

@section('title', 'Cashier Dashboard')
@section('meta_description', 'Cashier dashboard with a summary of daily activities.')

@section('content')
    <!-- Dashboard Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Cashier Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}. Here is a summary of your activities today.</p>
    </div>

    @if(!$hasOpeningBalance)
        <div class="mb-8 bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md shadow-md" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="h-6 w-6 text-yellow-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.21 3.031-1.742 3.031H4.42c-1.532 0-2.492-1.697-1.742-3.031l5.58-9.92zM10 13a1 1 0 110-2 1 1 0 010 2zm-1-4a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold">Important: Opening Balance Required</p>
                    <p class="text-sm">You have not recorded your opening balance for today. Please do so to ensure accurate transaction records.</p>
                    <a href="{{ route('opening-balances.create') }}" class="mt-2 inline-block bg-yellow-500 text-white font-bold py-2 px-4 rounded hover:bg-yellow-600 transition-colors">
                        Record Opening Balance
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Opening Balances Grid -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Today's Opening Balances</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($openingBalances as $balance)
                <div class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm opacity-80">{{ $balance->wallet->currency->code }}</p>
                        </div>
                        <span class="text-1xl font-bold">{{ number_format($balance->amount, 2) }}</span>
                    </div>
                </div>
            @empty
                <div class="lg:col-span-3 bg-white rounded-xl shadow p-6 text-center text-gray-500">
                    <p>No opening balances have been recorded for today.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Profit Today Card -->
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl shadow-lg overflow-hidden text-white">
            <div class="p-6 flex items-center">
                <div class="p-3 rounded-full bg-white bg-opacity-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-medium opacity-80">Your Profit Today</h3>
                    <p class="text-1xl font-bold">{{ number_format($profitToday, 2) }}</p>
                </div>
            </div>
            <div class="px-6 py-2 bg-green-700 bg-opacity-30 text-xs font-medium">
                Based on your sales
            </div>
        </div>

        <!-- Total Purchases Card -->
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 rounded-xl shadow-lg overflow-hidden text-white">
            <div class="p-6 flex items-center">
                <div class="p-3 rounded-full bg-white bg-opacity-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-medium opacity-80">Your Purchases</h3>
                    <p class="text-1xl font-bold">{{ number_format($totalPurchases, 2) }}</p>
                    <p class="text-xs opacity-80 mt-1">{{ $purchaseCount }} transactions</p>
                </div>
            </div>
            <div class="px-6 py-2 bg-amber-700 bg-opacity-30 text-xs font-medium">
                Total for today
            </div>
        </div>

        <!-- Total Sales Card -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-xl shadow-lg overflow-hidden text-white">
            <div class="p-6 flex items-center">
                <div class="p-3 rounded-full bg-white bg-opacity-20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <h3 class="text-sm font-medium opacity-80">Your Sales</h3>
                    <p class="text-1xl font-bold">{{ number_format($totalSales, 2) }}</p>
                    <p class="text-xs opacity-80 mt-1">{{ $salesCount }} transactions</p>
                </div>
            </div>
            <div class="px-6 py-2 bg-red-700 bg-opacity-30 text-xs font-medium">
                Total for today
            </div>
        </div>
    </div>
   <!-- Quick Links -->
   <div class="mb-8">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Links</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <a href="{{ route('cashier.transactions.create') }}" class="group block p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h.01M12 7h.01M16 7h.01M9 17h6M12 21a9 9 0 01-9-9 9 9 0 019-9 9 9 0 019 9 9 9 0 01-9 9z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">New Transaction</h3>
                    <p class="text-sm text-gray-500">Record a new sale or purchase</p>
                </div>
            </div>
        </a>
        <a href="{{ route('cashier.cash-deposits.index') }}" class="group block p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900 group-hover:text-green-600 transition-colors">Cash Deposit</h3>
                    <p class="text-sm text-gray-500">Record a new cash deposit</p>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Today's Transactions Table -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Today's Transactions</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wallet</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ substr($transaction->id, 0, 8) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'sale' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            ₦{{ number_format($transaction->amount_in_ngn, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->currency->code ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $transaction->created_at->format('M d, H:i A') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No transactions were recorded today.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
