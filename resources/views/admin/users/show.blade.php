@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">User Details</h2>
    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
        <i class="fas fa-arrow-left mr-2"></i> Back to Users
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- User Profile Card -->
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <div class="flex flex-col items-center">
                <div class="w-24 h-24 bg-indigo-100 rounded-full flex items-center justify-center mb-4">
                    <span class="text-4xl font-bold text-indigo-600">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <h3 class="text-xl font-bold">{{ $user->name }}</h3>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                <span class="mt-2 px-3 py-1 font-semibold leading-tight text-xs rounded-full
                    {{ $user->isAdmin() ? 'bg-red-100 text-red-700' : ($user->isManager() ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
            <div class="mt-6 border-t pt-4">
                <p class="text-sm text-gray-600 flex justify-between">
                    <span class="font-medium">Joined:</span>
                    <span>{{ $user->created_at->format('d M, Y') }}</span>
                </p>
                <p class="text-sm text-gray-600 mt-2 flex justify-between">
                    <span class="font-medium">Total Transactions:</span>
                    <span class="font-bold">{{ $user->transactions->count() }}</span>
                </p>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.users.edit', $user) }}" class="w-full text-center block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">Edit User</a>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="lg:col-span-2 space-y-8">
        <!-- Recent Transactions -->
        <div class="bg-white rounded-lg shadow-md">
            <h3 class="font-bold text-lg p-4 border-b">Recent Transactions</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-600 font-medium">Type</th>
                            <th class="px-4 py-2 text-left text-gray-600 font-medium">Currency</th>
                            <th class="px-4 py-2 text-left text-gray-600 font-medium">Amount</th>
                            <th class="px-4 py-2 text-left text-gray-600 font-medium">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($user->transactions()->with('currency')->latest()->take(10)->get() as $tx)
                        <tr>
                            <td class="px-4 py-3"><x-rate-badge :type="$tx->type"/></td>
                            <td class="px-4 py-3 font-mono">{{ $tx->currency->code }}</td>
                            <td class="px-4 py-3">₦{{ number_format($tx->amount, 2) }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $tx->created_at->format('d M, Y h:ia') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-4 text-center text-gray-500">No transactions recorded yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Activity Log -->
        <div class="bg-white rounded-lg shadow-md">
            <h3 class="font-bold text-lg p-4 border-b">Activity Log</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-600 font-medium">Action</th>
                            <th class="px-4 py-2 text-left text-gray-600 font-medium">Description</th>
                            <th class="px-4 py-2 text-left text-gray-600 font-medium">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($user->activityLogs()->latest()->take(15)->get() as $log)
                        <tr>
                            <td class="px-4 py-3">{{ ucfirst($log->action) }}</td>
                            <td class="px-4 py-3">{{ $log->description }}</td>
                            <td class="px-4 py-3 text-gray-500">{{ $log->created_at->format('d M, Y h:ia') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-center text-gray-500">No activity recorded yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection