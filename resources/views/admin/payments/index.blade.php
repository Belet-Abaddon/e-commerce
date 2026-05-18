@extends('layouts.admin')

@section('title', 'Payments - HomeNest Furniture')
@section('header', 'Payments')

@section('content')
<div class="space-y-6">
    <!-- Payment Type Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-gray-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Payments</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $typeCounts['all'] }}</p>
                </div>
                <i class="fas fa-credit-card text-3xl text-gray-400"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Bank Transfer</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $typeCounts['bank_transfer'] }}</p>
                </div>
                <i class="fas fa-university text-3xl text-blue-400"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Credit Card</p>
                    <p class="text-2xl font-bold text-green-600">{{ $typeCounts['credit_card'] }}</p>
                </div>
                <i class="fab fa-cc-visa text-3xl text-green-400"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Cash on Delivery</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $typeCounts['cash_on_delivery'] }}</p>
                </div>
                <i class="fas fa-money-bill text-3xl text-yellow-400"></i>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Mobile Payment</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $typeCounts['mobile_payment'] }}</p>
                </div>
                <i class="fas fa-mobile-alt text-3xl text-purple-400"></i>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white rounded-xl shadow-md p-4">
        <form method="GET" action="{{ route('admin.payments.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by payment name..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-admin-light-blue">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Type</label>
                <select name="payment_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-admin-light-blue">
                    <option value="">All Types</option>
                    <option value="bank_transfer" {{ request('payment_type') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                    <option value="credit_card" {{ request('payment_type') == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                    <option value="cash_on_delivery" {{ request('payment_type') == 'cash_on_delivery' ? 'selected' : '' }}>Cash on Delivery</option>
                    <option value="mobile_payment" {{ request('payment_type') == 'mobile_payment' ? 'selected' : '' }}>Mobile Payment</option>
                </select>
            </div>
            <div class="md:col-span-3 flex justify-end space-x-2">
                <button type="submit" class="px-4 py-2 bg-admin-blue text-white rounded-lg hover:bg-admin-light-blue">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.payments.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    <i class="fas fa-undo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Screenshot</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $payment->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            #{{ str_pad($payment->order_id, 6, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $payment->order->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $payment->order->user->email }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($payment->payment_type == 'bank_transfer') bg-blue-100 text-blue-700
                                @elseif($payment->payment_type == 'credit_card') bg-green-100 text-green-700
                                @elseif($payment->payment_type == 'cash_on_delivery') bg-yellow-100 text-yellow-700
                                @else bg-purple-100 text-purple-700
                                @endif">
                                {{ str_replace('_', ' ', ucfirst($payment->payment_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $payment->payment_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($payment->screenshot)
                                <a href="{{ route('admin.payments.show', $payment->id) }}" class="text-green-600 hover:text-green-700">
                                    <i class="fas fa-image"></i> View
                                </a>
                            @else
                                <span class="text-gray-400">No file</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $payment->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('admin.payments.show', $payment->id) }}" class="text-admin-blue hover:text-admin-light-blue">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button onclick="deletePayment({{ $payment->id }})" class="text-red-600 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            No payments found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Section -->
        <div class="px-6 py-4 border-t bg-gray-50">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-sm text-gray-500">
                    Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of {{ $payments->total() }} payments
                </div>
                <div>
                    {{ $payments->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function deletePayment(id) {
        if (confirm('Are you sure you want to delete this payment record?')) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/payments/${id}`;
            form.submit();
        }
    }
</script>
@endsection