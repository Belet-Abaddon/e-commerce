@extends('layouts.admin')

@section('title', 'Delivery Details')
@section('header', 'Delivery Details')

@section('content')
    <div class="py-6">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Delivery Details</h1>
                        <p class="text-sm text-gray-600 mt-1">View and manage delivery information</p>
                    </div>
                    <a href="{{ route('admin.deliveries.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200 shadow-sm">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Deliveries
                    </a>
                </div>
            </div>

            <!-- Delivery Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Delivery Status</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sm text-gray-600 font-medium">Current Milestone Status</p>
                            <select onchange="updateDeliveryStatus({{ $delivery->id }}, this.value)" class="mt-2 px-4 py-2 text-sm rounded-lg font-semibold border border-gray-300 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500
                                    @if($delivery->delivery_status == 'pending') bg-yellow-100 text-yellow-700
                                    @elseif($delivery->delivery_status == 'in_progress') bg-blue-100 text-blue-700
                                    @else bg-green-100 text-green-700
                                    @endif">
                                <option value="pending" {{ $delivery->delivery_status == 'pending' ? 'selected' : '' }}>⏳
                                    Pending</option>
                                <option value="in_progress" {{ $delivery->delivery_status == 'in_progress' ? 'selected' : '' }}>🔄 In Progress</option>
                                <option value="delivered" {{ $delivery->delivery_status == 'delivered' ? 'selected' : '' }}>✅
                                    Delivered</option>
                            </select>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">Last System Update</p>
                            <p class="text-sm font-bold text-gray-900 mt-1">
                                {{ $delivery->updated_at->format('F d, Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- Progress Bar Steps Component -->
                    <div class="relative mt-4">
                        <div class="flex justify-between mb-2">
                            <div class="text-center flex-1">
                                <div
                                    class="text-xs font-semibold {{ $delivery->delivery_status == 'pending' ? 'text-yellow-600' : 'text-blue-600' }}">
                                    Pending Dispatch
                                </div>
                            </div>
                            <div class="text-center flex-1">
                                <div
                                    class="text-xs font-semibold {{ $delivery->delivery_status == 'in_progress' ? 'text-blue-600' : ($delivery->delivery_status == 'delivered' ? 'text-blue-600' : 'text-gray-400') }}">
                                    In Transit
                                </div>
                            </div>
                            <div class="text-center flex-1">
                                <div
                                    class="text-xs font-semibold {{ $delivery->delivery_status == 'delivered' ? 'text-green-600' : 'text-gray-400' }}">
                                    Arrived / Delivered
                                </div>
                            </div>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            @php
                                $progress = 0;
                                if ($delivery->delivery_status == 'pending')
                                    $progress = 33;
                                elseif ($delivery->delivery_status == 'in_progress')
                                    $progress = 66;
                                elseif ($delivery->delivery_status == 'delivered')
                                    $progress = 100;
                            @endphp
                            <div class="bg-blue-600 rounded-full h-2.5 transition-all duration-500"
                                style="width: {{ $progress }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items Detail Manifest (New Section Added) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">
                        <i class="fas fa-box-open mr-2 text-blue-500"></i>Ordered Items Manifest
                    </h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2.5 py-1 rounded-full">
                        {{ $delivery->order->products->count() }} Distinct Line Items
                    </span>
                </div>
                <div class="p-0 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Product Info</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Original Price</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price Paid</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Quantity Ordered</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @php $calculatedRunningTotal = 0; @endphp
                            @if($delivery->order && $delivery->order->products)
                                @forelse($delivery->order->products as $product)
                                    @php
                                        $quantity = $product->pivot->qty ?? 1;
                                        $priceAtOrder = isset($product->price_at_order) ? $product->price_at_order : $product->price;
                                        $hasPromotion = isset($product->has_promotion_at_order) ? $product->has_promotion_at_order : false;
                                        $discountPercentage = isset($product->discount_percentage) ? $product->discount_percentage : 0;
                                        $lineItemTotal = $priceAtOrder * $quantity;
                                        $calculatedRunningTotal += $lineItemTotal;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-12 w-12 bg-gray-100 border rounded-lg overflow-hidden flex items-center justify-center">
                                                    @if($product->images && $product->images->count() > 0)
                                                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}"
                                                            class="h-full w-full object-cover">
                                                    @else
                                                        <i class="fas fa-image text-gray-400"></i>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $product->name }}</div>
                                                    <div class="text-xs text-gray-400">ID Ref: #{{ $product->id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-400 line-through">
                                            ${{ number_format($product->price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            @if($hasPromotion)
                                                <div>
                                                    <span
                                                        class="text-sm font-semibold text-red-600">${{ number_format($priceAtOrder, 2) }}</span>
                                                    <span
                                                        class="ml-1 text-xs bg-red-100 text-red-700 px-1 py-0.5 rounded-full">-{{ $discountPercentage }}%</span>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-600">${{ number_format($priceAtOrder, 2) }}</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-900">
                                            {{ $quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-blue-600">
                                            ${{ number_format($lineItemTotal, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                            No items recorded on this order line manifest.
                                        </td>
                                    </tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>
                <!-- Invoice Summary Total footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end">
                    <div class="text-right">
                        <span class="text-xs font-semibold uppercase text-gray-500 block">Total Cart Value Balance</span>
                        <span
                            class="text-xl font-black text-gray-900">${{ number_format($calculatedRunningTotal, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Order Information Metadata -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-shopping-cart mr-2 text-blue-500"></i>
                            Order Information
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Order Reference:</span>
                            <span class="text-sm font-bold text-gray-900">#{{ $delivery->order_id }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Order Creation Date:</span>
                            <span
                                class="text-sm font-semibold text-gray-800">{{ $delivery->order->created_at->format('F d, Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Delivery Fulfillment Method:</span>
                            <span class="px-2 py-0.5 text-xs font-bold rounded-md bg-purple-100 text-purple-800">
                                {{ ucfirst($delivery->order->delivery_type ?? 'Standard Carriage') }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Consignee Delivery Name:</span>
                            <span
                                class="text-sm font-semibold text-gray-800">{{ $delivery->order->delivery_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Customer Profile Contact Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-user mr-2 text-blue-500"></i>
                            Customer Profile Details
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Account Username:</span>
                            <span
                                class="text-sm font-semibold text-gray-800">{{ $delivery->order->user->name ?? 'Deleted Profile Account' }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2">
                            <span class="text-sm font-medium text-gray-500">Email Address:</span>
                            <a href="mailto:{{ $delivery->order->user->email ?? '#' }}"
                                class="text-sm font-semibold text-blue-600 hover:underline">{{ $delivery->order->user->email ?? 'N/A' }}</a>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-500">Primary Contact Phone:</span>
                            <span
                                class="text-sm font-bold text-gray-800">{{ $delivery->order->user->ph_number ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Full Delivery Shipping Address Block -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden lg:col-span-2">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                            Destination Shipping Address
                        </h3>
                    </div>
                    <div class="p-6">
                        <div
                            class="bg-gray-50 border rounded-lg p-4 font-mono text-sm text-gray-800 leading-relaxed shadow-inner">
                            {{ $delivery->order->order_address ?? 'No detailed shipping destination data attached to order profile records.' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Footer Actions Area -->
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="showTracking({{ $delivery->id }})"
                    class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg font-medium flex items-center gap-2 shadow-sm transition">
                    <i class="fas fa-map-marker-alt"></i> Open Real-Time Tracker Timeline
                </button>
            </div>
        </div>
    </div>

    <!-- Tracking History Log Dynamic Timeline Modal -->
    <div id="trackingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-xl rounded-xl bg-white">
            <div class="flex justify-between items-center mb-4 pb-2 border-b">
                <h3 class="text-lg font-bold text-gray-900">Delivery Tracking History</h3>
                <button onclick="closeTrackingModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="trackingHistory" class="space-y-4 max-h-96 overflow-y-auto pr-1">
                <!-- Loaded dynamically via Fetch Request -->
            </div>
        </div>
    </div>

    <script>
        // Isolated State Update Handler
        function updateDeliveryStatus(deliveryId, status) {
            fetch(`/admin/deliveries/${deliveryId}/update-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ delivery_status: status })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification('Fulfillment status state shifted successfully!', 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 800);
                    } else {
                        showNotification('Server rejected status state parameters change.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Communication channel error processing request.', 'error');
                });
        }

        // Open and load Tracking History Stream Data Pipeline 
        function showTracking(deliveryId) {
            fetch(`/admin/deliveries/${deliveryId}/tracking`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const trackingHtml = data.tracking_history.map((item, index) => `
                        <div class="flex items-start gap-3 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                            <div class="flex-shrink-0 mt-0.5">
                                <div class="w-8 h-8 ${item.completed ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400'} rounded-full flex items-center justify-center">
                                    <i class="fas ${item.completed ? 'fa-check-circle' : 'fa-dot-circle'} text-sm"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-gray-900 text-sm">${item.status}</p>
                                <p class="text-xs text-gray-600 mt-0.5 leading-relaxed">${item.description}</p>
                                <p class="text-[10px] text-gray-400 mt-1 font-mono">${new Date(item.date).toLocaleString()}</p>
                            </div>
                        </div>
                    `).join('');

                        document.getElementById('trackingHistory').innerHTML = trackingHtml;
                        document.getElementById('trackingModal').classList.remove('hidden');
                    }
                });
        }

        function closeTrackingModal() {
            document.getElementById('trackingModal').classList.add('hidden');
        }

        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `fixed top-20 right-4 z-50 px-6 py-3 rounded-lg shadow-xl text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} transition-all duration-300 transform opacity-100`;
            notification.innerHTML = `
            <div class="flex items-center gap-2 font-medium text-sm">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                <span>${message}</span>
            </div>
        `;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Modal closing click event background capture configuration
        document.getElementById('trackingModal').addEventListener('click', function (e) {
            if (e.target === this) {
                closeTrackingModal();
            }
        });
    </script>
@endsection