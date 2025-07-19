@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">User Management</h2>
    <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
        <i class="fas fa-plus"></i> Add New User
    </a>
</div>

<div class="bg-white rounded-lg shadow">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead>
                <tr class="bg-gray-50">
                    <th class="px-4 py-2 text-left">Name</th>
                    <th class="px-4 py-2 text-left">Email</th>
                    <th class="px-4 py-2 text-left">Role</th>
                    <th class="px-4 py-2 text-left">Joined</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                <tr>
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 font-semibold leading-tight text-xs rounded-full
                            {{ $user->isAdmin() ? 'bg-red-100 text-red-700' : ($user->isManager() ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700') }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $user->created_at->format('d M, Y') }}</td>
                    <td class="px-4 py-2 flex items-center space-x-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-blue-500 hover:text-blue-700"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800"><i class="fas fa-pencil-alt"></i></a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-gray-500">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        {{ $users->links() }}
    </div>
</div>
@endsection