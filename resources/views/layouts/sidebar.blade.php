<div id="sidebar" class="h-screen bg-gray-800 text-white w-64 fixed top-0 left-0 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out z-30">
    <div class="p-4">
        <h1 class="text-2xl font-bold">Bureau De Change</h1>
    </div>
    <nav class="mt-10">
        @if (Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Dashboard</a>
            <a href="{{ route('opening-balances.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Opening Balances</a>
            <a href="{{ route('admin.wallets.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Wallets</a>
            <a href="{{ route('admin.cash-deposits.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Cash Deposit</a>
            <a href="{{ route('admin.transactions.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Transactions</a>
            <a href="{{ route('admin.rates.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Rates</a>
            <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Users</a>
            <a href="{{ route('admin.settings.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Settings</a>
        @elseif (Auth::user()->isManager())
            <a href="{{ route('manager.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Dashboard</a>
            <a href="{{ route('opening-balances.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Opening Balances</a>
            <a href="{{ route('manager.wallets.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Wallets</a>
            <a href="{{ route('admin.cash-deposits.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Cash Deposit</a>
            <a href="{{ route('manager.transactions.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Transactions</a>
            <a href="{{ route('manager.rates.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Rates</a>
            <a href="{{ route('manager.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Users</a>
        @else
            <a href="{{ route('cashier.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Dashboard</a>
            <a href="{{ route('opening-balances.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Opening Balances</a>
            <a href="{{ route('cashier.cash-deposits.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Cash Deposit</a>
            <a href="{{ route('cashier.transactions.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">Transactions</a>
        @endif
    </nav>
    <div class="absolute bottom-0 w-full">
        <div class="p-4 border-t border-gray-700">
            <span class="block text-sm">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left mt-2 text-sm text-gray-400 hover:text-white">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
