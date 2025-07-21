@extends('layouts.app')

@section('title', 'Manager Dashboard')
@section('meta_description', 'Manager dashboard with a comprehensive overview of the application.')

@section('content')
    <!-- Dashboard Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Manager Dashboard</h1>
        <p class="text-gray-600">Welcome back, {{ Auth::user()->name }}. Here's the latest from your team.</p>
    </div>

    <!-- Live Exchange Rate Ticker -->
    <div class="mb-8 bg-gray-800 rounded-xl shadow-lg p-2 overflow-hidden">
        <iframe src="https://www.exchangerates.org.uk/widget/ER-LRTICKER.php?w=1200&s=1&mc=NGN&mbg=1F2937&bs=no&bc=1F2937&f=verdana&fs=12px&fc=FFFFFF&lc=FFFFFF&lhc=FE9A00&vc=FFFFFF&vcu=10B981&vcd=EF4444&" width="100%" height="30" frameborder="0" scrolling="no" marginwidth="0" marginheight="0"></iframe>
    </div>

    <!-- Opening Balances Grid -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Today's Opening Balances</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($openingBalances as $balance)
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
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
                    <h3 class="text-sm font-medium opacity-80">Profit Today</h3>
                    <p class="text-1xl font-bold">{{ number_format($profitToday, 2) }}</p>
                </div>
            </div>
            <div class="px-6 py-2 bg-green-700 bg-opacity-30 text-xs font-medium">
                {{ now()->format('l, F j') }}
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
                    <h3 class="text-sm font-medium opacity-80">Total Purchases</h3>
                    <p class="text-1xl font-bold">{{ number_format($totalPurchases, 2) }}</p>
                    <p class="text-xs opacity-80 mt-1">{{ $purchaseCount }} transactions</p>
                </div>
            </div>
            <div class="px-6 py-2 bg-amber-700 bg-opacity-30 text-xs font-medium">
                Avg. {{ $purchaseCount > 0 ? number_format($totalPurchases/$purchaseCount, 2) : '0.00' }} per transaction
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
                    <h3 class="text-sm font-medium opacity-80">Total Sales</h3>
                    <p class="text-1xl font-bold">{{ number_format($totalSales, 2) }}</p>
                    <p class="text-xs opacity-80 mt-1">{{ $salesCount }} transactions</p>
                </div>
            </div>
            <div class="px-6 py-2 bg-red-700 bg-opacity-30 text-xs font-medium">
                Avg. {{ $salesCount > 0 ? number_format($totalSales/$salesCount, 2) : '0.00' }} per sale
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Quick Links</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- New Transaction -->
            <a href="{{ route('manager.transactions.create') }}" class="group block p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">New Transaction</h3>
                        <p class="text-sm text-gray-500">Record a new sale or purchase</p>
                    </div>
                </div>
            </a>

            <!-- Manage Staff -->
            <a href="{{ route('manager.users.index') }}" class="group block p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-purple-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.122-1.28-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.122-1.28.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-purple-600 transition-colors">Manage Staff</h3>
                        <p class="text-sm text-gray-500">View and manage your cashiers</p>
                    </div>
                </div>
            </a>

            <!-- Manage Rates -->
            <a href="{{ route('manager.rates.index') }}" class="group block p-6 bg-white rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-12 w-12 rounded-full bg-teal-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-teal-600 transition-colors">Manage Rates</h3>
                        <p class="text-sm text-gray-500">Set buy and sell exchange rates</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Team and Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Team Overview -->
        <div class="lg:col-span-1 bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Team Overview</h3>
                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">
                        {{ $managers->count() + $cashiers->count() }} members
                    </span>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                <!-- Managers Section -->
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                        <h4 class="font-medium text-gray-700">Managers</h4>
                        <span class="ml-auto text-sm text-gray-500">{{ $managers->count() }}</span>
                    </div>
                    <ul class="space-y-3">
                        @forelse($managers as $manager)
                            <li class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                    <span class="text-purple-600 font-medium">{{ substr($manager->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $manager->name }}</p>
                                    <p class="text-xs text-gray-500 truncate" style="max-width: 150px;">{{ $manager->email }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="text-center py-4 text-gray-500">No managers found</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Cashiers Section -->
                <div class="p-6">
                    <div class="flex items-center mb-4">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                        <h4 class="font-medium text-gray-700">Cashiers</h4>
                        <span class="ml-auto text-sm text-gray-500">{{ $cashiers->count() }}</span>
                    </div>
                    <ul class="space-y-3">
                        @forelse($cashiers as $cashier)
                            <li class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                    <span class="text-green-600 font-medium">{{ substr($cashier->name, 0, 1) }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $cashier->name }}</p>
                                    <p class="text-xs text-gray-500 truncate" style="max-width: 150px;">{{ $cashier->email }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="text-center py-4 text-gray-500">No cashiers found</li>
                        @endforelse
                    </ul>
                </div>
    </div>
</div>

        <!-- Activity Overview -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">Recent Activity</h3>
            </div>
            <div class="divide-y divide-gray-200">
    <div class="p-6">
                    <div class="space-y-4">
                        @forelse($transactions->take(5) as $transaction)
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    @if($transaction->type === 'sale')
                                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                        </div>
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900">{{ $transaction->user->name ?? 'System' }} - {{ ucfirst($transaction->type) }}</p>
                                        <p class="text-sm font-bold @if($transaction->type === 'sale') text-green-600 @else text-red-600 @endif">
                                            {{ number_format($transaction->amount_in_ngn, 2) }}
                                        </p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $transaction->created_at->diffForHumans() }} • <span class="text-gray-400">Ref: {{ substr($transaction->id, 0, 8) }}</span></p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-500">No recent transactions found</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Today's Transactions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
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
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $transaction->user->name ?? 'System' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $transaction->type === 'sale' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($transaction->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                                {{ number_format($transaction->amount_in_ngn, 2) }}
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No transactions were recorded today.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
