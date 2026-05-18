@extends('layouts.admin')

@section('title', 'Order Details - HomeNest Furniture')
@section('header', 'Order Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-admin-blue hover:text-admin-light-blue">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Orders
        </a>
    </div>

    <!-- Order Information Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Order Details -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-admin-blue to-admin-light-blue px-6 py-4">
                <h3 class="text-white font-semibold text-lg">Order Information</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Order ID:</span>
                        <span class="text-sm font-semibold text-gray-900">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Order Date:</span>
                        <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($order->order_date)->format('F d, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Delivery Type:</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($order->delivery_type == 'express') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-700
                            @endif">
                            {{ ucfirst($order->delivery_type) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Delivery Name:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $order->delivery_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Total Quantity:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $order->qty }} items</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-500">Delivery Address:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $order->order_address }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-admin-blue to-admin-light-blue px-6 py-4">
                <h3 class="text-white font-semibold text-lg">Customer Information</h3>
            </div>
            <div class="p-6">
                <div class="flex items-center space-x-4 mb-4 pb-4 border-b">
                    <div class="w-12 h-12 rounded-full bg-admin-light-blue flex items-center justify-center">
                        <i class="fas fa-user text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">{{ $order->user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Phone Number:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $order->user->ph_number ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Customer Since:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $order->user->created_at->format('F d, Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-500">Total Orders:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $order->user->orders->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products in Order -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-admin-blue to-admin-light-blue px-6 py-4">
            <h3 class="text-white font-semibold text-lg">Order Items</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Brand</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($product->images && $product->images->first())
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-10 h-10 rounded-lg object-cover mr-3">
                                @else
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-box text-gray-400"></i>
                                    </div>
                                @endif
                                <span class="font-medium text-gray-900">{{ $product->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $product->productType->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $product->brand->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-admin-blue">
                            ${{ number_format($product->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $order->qty }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-admin-blue">
                            ${{ number_format($product->price * $order->qty, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-2"></i>
                            <p>No products found for this order</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-right font-semibold text-gray-800">Total Amount:</td>
                        <td class="px-6 py-4 text-xl font-bold text-admin-blue">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Order Timeline -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gradient-to-r from-admin-blue to-admin-light-blue px-6 py-4">
            <h3 class="text-white font-semibold text-lg">Order Timeline</h3>
        </div>
        <div class="p-6">
            <div class="relative">
                <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                <div class="space-y-6">
                    <!-- Order Placed -->
                    <div class="relative flex items-start">
                        <div class="absolute left-0 w-8 h-8 rounded-full bg-green-500 flex items-center justify-center z-10">
                            <i class="fas fa-check text-white text-sm"></i>
                        </div>
                        <div class="ml-12">
                            <p class="font-semibold text-gray-900">Order Placed</p>
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($order->order_date)->format('F d, Y') }}</p>
                        </div>
                    </div>
                    
                    <!-- Order Confirmation (1 day after order date) -->
                    @php
                        $confirmationDate = \Carbon\Carbon::parse($order->order_date)->addDays(1);
                    @endphp
                    <div class="relative flex items-start">
                        <div class="absolute left-0 w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center z-10">
                            <i class="fas fa-check-circle text-white text-sm"></i>
                        </div>
                        <div class="ml-12">
                            <p class="font-semibold text-gray-900">Order Confirmed</p>
                            <p class="text-sm text-gray-500">{{ $confirmationDate->format('F d, Y') }}</p>
                            @if($confirmationDate > now())
                                <p class="text-xs text-gray-400">Expected</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Estimated Delivery (based on delivery type) -->
                    @php
                        $deliveryDays = $order->delivery_type == 'express' ? 3 : 7;
                        $estimatedDelivery = \Carbon\Carbon::parse($order->order_date)->addDays($deliveryDays);
                    @endphp
                    <div class="relative flex items-start">
                        <div class="absolute left-0 w-8 h-8 rounded-full bg-purple-500 flex items-center justify-center z-10">
                            <i class="fas fa-truck text-white text-sm"></i>
                        </div>
                        <div class="ml-12">
                            <p class="font-semibold text-gray-900">Estimated Delivery</p>
                            <p class="text-sm text-gray-500">{{ $estimatedDelivery->format('F d, Y') }}</p>
                            <p class="text-xs text-gray-400">Delivery Type: {{ ucfirst($order->delivery_type) }}</p>
                            @if($estimatedDelivery < now())
                                <p class="text-xs text-green-600 mt-1">Delivered</p>
                            @elseif($estimatedDelivery == now())
                                <p class="text-xs text-orange-600 mt-1">Expected today</p>
                            @else
                                <p class="text-xs text-gray-400 mt-1">Expected delivery</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex justify-end space-x-3">
        <button onclick="deleteOrder({{ $order->id }})" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
            <i class="fas fa-trash mr-2"></i> Delete Order
        </button>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function deleteOrder(orderId) {
        if (confirm('Are you sure you want to delete this order? This action cannot be undone.')) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/orders/${orderId}`;
            form.submit();
        }
    }
</script>
@endsection