@extends('layouts.admin')

@section('title', 'Customer Details - HomeNest Furniture')
@section('header', 'Customer Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.customers.index') }}" class="inline-flex items-center text-admin-blue hover:text-admin-light-blue">
            <i class="fas fa-arrow-left mr-2"></i> Back to Customers
        </a>
        @if($customer->id != auth()->id())
        <button onclick="deleteCustomer({{ $customer->id }})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            <i class="fas fa-trash mr-2"></i> Delete Account
        </button>
        @endif
    </div>

    <!-- Customer Information -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">Customer Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">ID</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $customer->id }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $customer->name }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $customer->email }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">Phone Number</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $customer->ph_number }}</p>
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">Address</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $customer->address }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">Role</label>
                        <p class="mt-1">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($customer->role == 'admin') bg-purple-100 text-purple-700
                                @else bg-green-100 text-green-700
                                @endif">
                                {{ $customer->role }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">Total Orders</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $orderCount }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">Joined Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $customer->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    @if($customer->orders->count() > 0)
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">Recent Orders</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($customer->orders->take(5) as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $order->qty }} items
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="text-admin-blue hover:text-admin-light-blue">
                                View Order
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Change Role Section -->
    @if($customer->id != auth()->id())
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">Change Role</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.customers.update-role', $customer->id) }}" method="POST" class="flex items-end gap-4">
                @csrf
                @method('PUT')
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Role</label>
                    <select name="role" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-admin-light-blue">
                        <option value="user" {{ $customer->role == 'user' ? 'selected' : '' }}>user</option>
                        <option value="admin" {{ $customer->role == 'admin' ? 'selected' : '' }}>admin</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2 bg-admin-blue text-white rounded-lg hover:bg-admin-light-blue">
                    Update Role
                </button>
            </form>
        </div>
    </div>
    @endif
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function deleteCustomer(id) {
        if (confirm('Are you sure you want to delete this customer account? This action cannot be undone and will delete all orders and feedback from this customer.')) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/customers/${id}`;
            form.submit();
        }
    }
</script>
@endsection