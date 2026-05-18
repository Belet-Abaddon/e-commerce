@extends('layouts.admin')

@section('title', 'Payment Details - HomeNest Furniture')
@section('header', 'Payment Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.payments.index') }}" class="inline-flex items-center text-admin-blue hover:text-admin-light-blue">
            <i class="fas fa-arrow-left mr-2"></i> Back to Payments
        </a>
        <button onclick="deletePayment({{ $payment->id }})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
            <i class="fas fa-trash mr-2"></i> Delete Payment
        </button>
    </div>

    <!-- Payment Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payment Details -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="font-semibold text-gray-800">Payment Information</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Payment ID:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $payment->id }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Order ID:</span>
                        <span class="text-sm font-semibold text-gray-900">#{{ str_pad($payment->order_id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Payment Type:</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($payment->payment_type == 'bank_transfer') bg-blue-100 text-blue-700
                            @elseif($payment->payment_type == 'credit_card') bg-green-100 text-green-700
                            @elseif($payment->payment_type == 'cash_on_delivery') bg-yellow-100 text-yellow-700
                            @else bg-purple-100 text-purple-700
                            @endif">
                            {{ str_replace('_', ' ', ucfirst($payment->payment_type)) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Payment Name:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $payment->payment_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Payment Date:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $payment->created_at->format('Y-m-d H:i:s') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Information -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b">
                <h3 class="font-semibold text-gray-800">Order Information</h3>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Customer:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $payment->order->user->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Email:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $payment->order->user->email }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Phone:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $payment->order->user->ph_number }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Order Date:</span>
                        <span class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($payment->order->order_date)->format('Y-m-d') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b">
                        <span class="text-sm text-gray-500">Total Quantity:</span>
                        <span class="text-sm font-medium text-gray-900">{{ $payment->order->qty }} items</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-500">Total Amount:</span>
                        <span class="text-lg font-bold text-admin-blue">${{ number_format($orderTotal, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Screenshot -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">Payment Screenshot</h3>
        </div>
        <div class="p-6">
            @if($payment->screenshot)
                @php
                    $screenshotUrl = asset('storage/' . $payment->screenshot);
                @endphp
                <div class="flex justify-center">
                    <img src="{{ $screenshotUrl }}" 
                         alt="Payment Screenshot" 
                         class="max-w-full h-auto rounded-lg shadow-md"
                         style="max-height: 500px;">
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ $screenshotUrl }}" download class="text-admin-blue hover:text-admin-light-blue">
                        <i class="fas fa-download mr-2"></i> Download Screenshot
                    </a>
                </div>
            @else
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-image text-4xl mb-2"></i>
                    <p>No screenshot uploaded for this payment</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Products in Order -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">Products in Order</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($payment->order->products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $product->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            ${{ number_format($product->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $payment->order->qty }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-admin-blue">
                            ${{ number_format($product->price * $payment->order->qty, 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-right font-semibold text-gray-800">Total:</td>
                        <td class="px-6 py-4 text-xl font-bold text-admin-blue">
                            ${{ number_format($orderTotal, 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Delivery Address -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">Delivery Address</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500">Delivery Name</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $payment->order->delivery_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500">Delivery Type</label>
                    <p class="mt-1 text-sm text-gray-900">{{ ucfirst($payment->order->delivery_type) }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500">Address</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $payment->order->order_address }}</p>
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