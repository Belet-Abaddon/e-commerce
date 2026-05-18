@extends('layouts.admin')

@section('title', 'Customers - HomeNest Furniture')
@section('header', 'Customers')

@section('content')
<div class="space-y-6">
    <!-- Role Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-admin-blue">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Customers</p>
                    <p class="text-2xl font-bold text-admin-blue">{{ $roleCounts['all'] }}</p>
                </div>
                <i class="fas fa-users text-3xl text-admin-light-blue"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Users</p>
                    <p class="text-2xl font-bold text-green-600">{{ $roleCounts['user'] }}</p>
                </div>
                <i class="fas fa-user text-3xl text-green-400"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Admins</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $roleCounts['admin'] }}</p>
                </div>
                <i class="fas fa-user-shield text-3xl text-purple-400"></i>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-xl shadow-md p-4">
        <form method="GET" action="{{ route('admin.customers.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by name, email or role..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-admin-light-blue">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-admin-light-blue">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                </select>
            </div>
            <div class="md:col-span-3 flex justify-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-admin-blue text-white rounded-lg hover:bg-admin-light-blue">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.customers.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    <i class="fas fa-undo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Customers Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($customers as $customer)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $customer->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $customer->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $customer->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $customer->ph_number }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                            {{ $customer->address }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($customer->role == 'admin') bg-purple-100 text-purple-700
                                @else bg-green-100 text-green-700
                                @endif">
                                {{ $customer->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $customer->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.customers.show', $customer->id) }}" class="text-admin-blue hover:text-admin-light-blue">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            No customers found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t">
            {{ $customers->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection