<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-blue-800 leading-tight">
                {{ __('My Orders') }}
            </h2>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">
                    Total Orders:
                </span>
                <span class="font-medium text-blue-600">
                    {{ $orders->total() }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Status Filter Tabs -->
            <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('user.orders.index') }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ !request('status') ? 'bg-blue-600 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        All Orders
                    </a>
                    <a href="{{ route('user.orders.index', ['status' => 'pending']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request('status') == 'pending' ? 'bg-yellow-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-clock mr-1"></i> Pending
                    </a>
                    <a href="{{ route('user.orders.index', ['status' => 'in_progress']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request('status') == 'in_progress' ? 'bg-blue-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-spinner mr-1"></i> In Progress
                    </a>
                    <a href="{{ route('user.orders.index', ['status' => 'delivered']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request('status') == 'delivered' ? 'bg-green-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-check-circle mr-1"></i> Delivered
                    </a>
                    <a href="{{ route('user.orders.index', ['status' => 'cancelled']) }}" 
                       class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 {{ request('status') == 'cancelled' ? 'bg-red-500 text-white shadow-md' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-times-circle mr-1"></i> Cancelled
                    </a>
                </div>
            </div>

            <!-- Orders List -->
            <div class="space-y-4">
                @forelse($orders as $order)
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden border border-gray-100">
                    <div class="p-6">
                        <div class="flex flex-wrap justify-between items-start mb-4">
                            <div>
                                <div class="flex items-center space-x-3 mb-2">
                                    <span class="text-sm font-medium text-gray-500">Order #</span>
                                    <span class="text-lg font-bold text-blue-600">{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <p class="text-sm text-gray-500">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    Placed on {{ \Carbon\Carbon::parse($order->order_date)->format('F d, Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if($order->delivery_status == 'delivered') bg-green-100 text-green-700
                                    @elseif($order->delivery_status == 'in_progress') bg-blue-100 text-blue-700
                                    @elseif($order->delivery_status == 'cancelled') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700
                                    @endif">
                                    @if($order->delivery_status == 'delivered')
                                        <i class="fas fa-check-circle mr-1"></i> Delivered
                                    @elseif($order->delivery_status == 'in_progress')
                                        <i class="fas fa-spinner mr-1"></i> In Progress
                                    @elseif($order->delivery_status == 'cancelled')
                                        <i class="fas fa-times-circle mr-1"></i> Cancelled
                                    @else
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    @endif
                                </span>
                                <p class="text-xl font-bold text-gray-800 mt-2">${{ number_format($order->total_amount, 2) }}</p>
                                <p class="text-xs text-gray-500">{{ $order->qty }} item(s)</p>
                            </div>
                        </div>
                        
                        <div class="border-t border-gray-100 pt-4 flex flex-wrap justify-between items-center">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-truck mr-1"></i>
                                Delivery: {{ ucfirst($order->delivery_type) }}
                            </div>
                            <a href="{{ route('user.orders.show', $order->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg text-sm font-medium transition-colors">
                                View Details
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-xl shadow-sm p-12 text-center">
                    <i class="fas fa-shopping-cart text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Orders Yet</h3>
                    <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ route('user.products.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                        Start Shopping
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $orders->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>