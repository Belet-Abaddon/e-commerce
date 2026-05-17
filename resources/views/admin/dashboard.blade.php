@extends('layouts.admin')

@section('title', 'Dashboard - HomeNest Furniture')
@section('header', 'Dashboard Overview')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <!-- Total Revenue Card -->
        <div class="bg-admin-white rounded-xl shadow-md p-4 sm:p-6 border-l-4 border-admin-light-blue hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-500 text-xs sm:text-sm">Total Revenue</p>
                    <p class="text-xl sm:text-2xl font-bold text-admin-blue">${{ number_format($totalRevenue, 2) }}</p>
                    <p class="text-green-600 text-xs mt-2">
                        <i class="fas fa-arrow-up"></i> From all orders
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                    <i class="fas fa-dollar-sign text-admin-light-blue text-base sm:text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Orders Card -->
        <div class="bg-admin-white rounded-xl shadow-md p-4 sm:p-6 border-l-4 border-admin-light-blue hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-500 text-xs sm:text-sm">Total Orders</p>
                    <p class="text-xl sm:text-2xl font-bold text-admin-blue">{{ number_format($totalOrders) }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                    <i class="fas fa-shopping-cart text-admin-light-blue text-base sm:text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Products Card -->
        <div class="bg-admin-white rounded-xl shadow-md p-4 sm:p-6 border-l-4 border-admin-light-blue hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-500 text-xs sm:text-sm">Total Products</p>
                    <p class="text-xl sm:text-2xl font-bold text-admin-blue">{{ number_format($totalProducts) }}</p>
                    <p class="text-green-600 text-xs mt-2">
                        <i class="fas fa-check-circle"></i> {{ $activeProducts }} active
                        @if($inactiveProducts > 0)
                        <span class="text-red-600 ml-1">{{ $inactiveProducts }} inactive</span>
                        @endif
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                    <i class="fas fa-couch text-admin-light-blue text-base sm:text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Customers Card -->
        <div class="bg-admin-white rounded-xl shadow-md p-4 sm:p-6 border-l-4 border-admin-light-blue hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-gray-500 text-xs sm:text-sm">Total Customers</p>
                    <p class="text-xl sm:text-2xl font-bold text-admin-blue">{{ number_format($totalUsers) }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                    <i class="fas fa-users text-admin-light-blue text-base sm:text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Second Row Stats -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-admin-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs">Brands</p>
                    <p class="text-xl font-bold text-admin-blue">{{ number_format($totalBrands) }}</p>
                </div>
                <i class="fas fa-trademark text-admin-light-blue text-xl"></i>
            </div>
        </div>
        
        <div class="bg-admin-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs">Categories</p>
                    <p class="text-xl font-bold text-admin-blue">{{ number_format($totalProductTypes) }}</p>
                </div>
                <i class="fas fa-tags text-admin-light-blue text-xl"></i>
            </div>
        </div>
        
        <div class="bg-admin-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs">Promotions</p>
                    <p class="text-xl font-bold text-admin-blue">{{ number_format($activePromotions) }}</p>
                </div>
                <i class="fas fa-gift text-admin-light-blue text-xl"></i>
            </div>
        </div>
        
        <div class="bg-admin-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs">Feedbacks</p>
                    <p class="text-xl font-bold text-admin-blue">{{ number_format($totalFeedbacks) }}</p>
                </div>
                <i class="fas fa-star text-admin-light-blue text-xl"></i>
            </div>
        </div>
        
        <div class="bg-admin-white rounded-xl shadow-md p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-xs">Avg Rating</p>
                    <p class="text-xl font-bold text-admin-blue">
                        @php
                            $avgRating = \App\Models\Feedback::avg('rating');
                        @endphp
                        {{ number_format($avgRating, 1) }}
                    </p>
                </div>
                <i class="fas fa-chart-line text-admin-light-blue text-xl"></i>
            </div>
        </div>
    </div>
    
    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-admin-white rounded-xl shadow-md p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-admin-blue mb-4">Monthly Sales {{ date('Y') }}</h3>
            @if($monthlySales->count() > 0)
                <canvas id="salesChart" class="w-full h-64"></canvas>
            @else
                <div class="h-64 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <i class="fas fa-chart-line text-4xl mb-2"></i>
                        <p>No sales data available</p>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Products by Category -->
        <div class="bg-admin-white rounded-xl shadow-md p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-admin-blue mb-4">Products by Category</h3>
            @if($productsByCategory->count() > 0)
                <canvas id="categoryChart" class="w-full h-64"></canvas>
            @else
                <div class="h-64 flex items-center justify-center text-gray-400">
                    <div class="text-center">
                        <i class="fas fa-chart-pie text-4xl mb-2"></i>
                        <p>No category data available</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Order Status Distribution -->
    <div class="bg-admin-white rounded-xl shadow-md p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold text-admin-blue mb-4">Order Status Distribution</h3>
        @if($orderStatusDistribution->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                @foreach($orderStatusDistribution as $status)
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <div class="text-2xl font-bold text-admin-blue">{{ $status->count }}</div>
                        <div class="text-sm text-gray-600 capitalize">{{ $status->delivery_status }}</div>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-admin-light-blue rounded-full h-2" style="width: {{ ($status->count / $totalOrders) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-400 py-8">
                No order status data available
            </div>
        @endif
    </div>
    
    <!-- Recent Orders Table -->
    <div class="bg-admin-white rounded-xl shadow-md p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
            <h3 class="text-base sm:text-lg font-semibold text-admin-blue">Recent Orders</h3>
            <a href="#" class="text-admin-light-blue hover:text-admin-blue text-sm">View All Orders →</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase hidden sm:table-cell">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentOrders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $order->order_number }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600">
                            {{ $order->customer_name }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 hidden sm:table-cell">
                            {{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap text-sm font-semibold text-admin-blue">
                            ${{ number_format($order->total_amount, 2) }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($order->status == 'Delivered') bg-green-100 text-green-700
                                @elseif($order->status == 'Processing') bg-yellow-100 text-yellow-700
                                @elseif($order->status == 'Shipped') bg-blue-100 text-blue-700
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ $order->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                            No orders found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Top Products and Recent Users Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Top Selling Products -->
        <div class="bg-admin-white rounded-xl shadow-md p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-admin-blue mb-4">Top Selling Products</h3>
            <div class="space-y-3">
                @forelse($topProducts as $product)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <p class="font-medium text-gray-800">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $product->brand }} • {{ $product->category }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-admin-blue">{{ $product->units_sold }} units</p>
                        <p class="text-xs text-gray-500">${{ number_format($product->revenue, 2) }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">
                    No product sales data available
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Recent Users -->
        <div class="bg-admin-white rounded-xl shadow-md p-4 sm:p-6">
            <h3 class="text-base sm:text-lg font-semibold text-admin-blue mb-4">New Customers (Last 7 Days)</h3>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-admin-light-blue flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">
                    No new users in the last 7 days
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Recent Feedbacks -->
    <div class="bg-admin-white rounded-xl shadow-md p-4 sm:p-6">
        <h3 class="text-base sm:text-lg font-semibold text-admin-blue mb-4">Recent Customer Feedback</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse($recentFeedbacks as $feedback)
            <div class="p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <p class="font-medium text-gray-800">{{ $feedback->user->name }}</p>
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-xs {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                        @endfor
                    </div>
                </div>
                <p class="text-sm text-gray-600">{{ Str::limit($feedback->feedback, 100) }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ $feedback->created_at->diffForHumans() }}</p>
            </div>
            @empty
            <div class="col-span-2 text-center text-gray-500 py-8">
                No feedback available
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    @if($monthlySales->count() > 0)
    // Monthly Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode($monthlySales->pluck('month')->map(function($month) {
                return date('F', mktime(0, 0, 0, $month, 1));
            })) !!},
            datasets: [{
                label: 'Sales ($)',
                data: {!! json_encode($monthlySales->pluck('total_sales')) !!},
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '$' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    @endif
    
    @if($productsByCategory->count() > 0)
    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($productsByCategory->pluck('name')) !!},
            datasets: [{
                data: {!! json_encode($productsByCategory->pluck('products_count')) !!},
                backgroundColor: [
                    '#3b82f6',
                    '#60a5fa',
                    '#93c5fd',
                    '#bfdbfe',
                    '#1e3a8a',
                    '#2563eb',
                    '#4f46e5'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11 } }
                }
            }
        }
    });
    @endif
</script>
@endsection