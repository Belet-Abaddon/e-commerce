<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-blue-800 leading-tight">
                    Order Details
                </h2>
                <p class="text-sm text-gray-500 mt-1">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
            <a href="{{ route('user.orders.index') }}"
                class="inline-flex items-center text-blue-600 hover:text-blue-700">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Order Information - Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Status Progress -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-6">Order Progress</h3>

                        <div class="relative">
                            <!-- Progress Bar Background -->
                            <div class="absolute top-5 left-0 w-full h-1 bg-gray-200 rounded-full"></div>

                            <!-- Progress Bar Fill -->
                            @php
                                $statusOrder = ['pending', 'in_progress', 'delivered'];
                                $currentIndex = array_search($order->delivery_status, $statusOrder);
                                $progressPercentage = (($currentIndex + 1) / count($statusOrder)) * 100;
                            @endphp
                            <div class="absolute top-5 left-0 h-1 bg-blue-600 rounded-full transition-all duration-500"
                                style="width: {{ $progressPercentage }}%"></div>

                            <!-- Status Steps -->
                            <div class="relative flex justify-between">
                                <div class="text-center">
                                    <div class="relative">
                                        <div
                                            class="w-10 h-10 rounded-full {{ $order->delivery_status == 'pending' || $currentIndex >= 0 ? 'bg-blue-600' : 'bg-gray-300' }} flex items-center justify-center mx-auto mb-2 shadow-md transition-all">
                                            <i class="fas fa-clock text-white text-sm"></i>
                                        </div>
                                        <span class="text-xs font-medium {{ $order->delivery_status == 'pending' || $currentIndex >= 0 ? 'text-blue-600' : 'text-gray-400' }}">Pending</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Order Placed</p>
                                </div>

                                <div class="text-center">
                                    <div class="relative">
                                        <div
                                            class="w-10 h-10 rounded-full {{ $order->delivery_status == 'in_progress' || $currentIndex >= 1 ? 'bg-blue-600' : 'bg-gray-300' }} flex items-center justify-center mx-auto mb-2 shadow-md transition-all">
                                            <i class="fas fa-spinner text-white text-sm"></i>
                                        </div>
                                        <span class="text-xs font-medium {{ $order->delivery_status == 'in_progress' || $currentIndex >= 1 ? 'text-blue-600' : 'text-gray-400' }}">In Progress</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Processing Order</p>
                                </div>

                                <div class="text-center">
                                    <div class="relative">
                                        <div
                                            class="w-10 h-10 rounded-full {{ $order->delivery_status == 'delivered' ? 'bg-green-600' : ($currentIndex >= 2 ? 'bg-green-600' : 'bg-gray-300') }} flex items-center justify-center mx-auto mb-2 shadow-md transition-all">
                                            <i class="fas fa-check-circle text-white text-sm"></i>
                                        </div>
                                        <span class="text-xs font-medium {{ $order->delivery_status == 'delivered' ? 'text-green-600' : ($currentIndex >= 2 ? 'text-green-600' : 'text-gray-400') }}">Delivered</span>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Order Completed</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b">
                            <h3 class="font-semibold text-gray-800">Order Items</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Original Price</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price Paid</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($order->products as $product)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center">
                                                    @if($product->images && $product->images->first())
                                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                                            alt="{{ $product->name }}"
                                                            class="w-12 h-12 rounded-lg object-cover mr-3">
                                                    @else
                                                        <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                                            <i class="fas fa-box text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $product->brand->name ?? 'N/A' }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-400 line-through">
                                                ${{ number_format($product->price, 2) }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($product->has_promotion_at_order)
                                                    <div>
                                                        <span class="text-sm font-semibold text-red-600">${{ number_format($product->price_at_order, 2) }}</span>
                                                        <span class="ml-1 text-xs bg-red-100 text-red-700 px-1 py-0.5 rounded-full">-{{ $product->discount_percentage }}%</span>
                                                    </div>
                                                @else
                                                    <span class="text-sm text-gray-600">${{ number_format($product->price, 2) }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->qty }}</td>
                                            <td class="px-6 py-4 text-sm font-semibold text-blue-600">
                                                ${{ number_format($product->price_at_order * $order->qty, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 text-right font-semibold text-gray-800">Total Amount:</td>
                                        <td class="px-6 py-4 text-xl font-bold text-blue-600">${{ number_format($order->total_amount, 2) }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Order Summary -->
                <div class="space-y-6">
                    <!-- Delivery Information -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-truck text-blue-600 mr-2"></i>
                            Delivery Information
                        </h3>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs text-gray-500">Delivery Name</p>
                                <p class="text-sm font-medium text-gray-800">{{ $order->delivery_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Delivery Type</p>
                                <p class="text-sm font-medium text-gray-800">{{ ucfirst($order->delivery_type) }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Delivery Address</p>
                                <p class="text-sm text-gray-600">{{ $order->order_address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="bg-white rounded-xl shadow-sm p-6">
                        <h3 class="font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Order Summary
                        </h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Order Date</span>
                                <span class="text-sm font-medium text-gray-800">{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Total Items</span>
                                <span class="text-sm font-medium text-gray-800">{{ $order->qty }} items</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Delivery Status</span>
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($order->delivery_status == 'delivered') bg-green-100 text-green-700
                                    @elseif($order->delivery_status == 'in_progress') bg-blue-100 text-blue-700
                                    @elseif($order->delivery_status == 'cancelled') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $order->delivery_status)) }}
                                </span>
                            </div>
                            <div class="border-t pt-3 mt-2">
                                <div class="flex justify-between">
                                    <span class="text-lg font-semibold text-gray-800">Total Paid</span>
                                    <span class="text-xl font-bold text-blue-600">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cancel Order Button (Only for pending orders) -->
                    @if($canCancel)
                        <div class="bg-red-50 rounded-xl p-6 border border-red-200">
                            <div class="flex items-start">
                                <i class="fas fa-exclamation-triangle text-red-500 mt-0.5 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-red-800 mb-1">Cancel Order</h4>
                                    <p class="text-sm text-red-600 mb-4">You can cancel this order as long as it hasn't been processed yet.</p>
                                    <form action="{{ route('user.orders.cancel', $order->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to cancel this order? This action cannot be undone.')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                                            <i class="fas fa-times-circle mr-2"></i>
                                            Cancel Order
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>