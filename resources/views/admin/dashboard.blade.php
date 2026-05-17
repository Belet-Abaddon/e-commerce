@extends('layouts.admin')

@section('title', 'Dashboard - HomeNest Furniture')

@section('header', 'Dashboard Overview')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Sales Card -->
        <div class="bg-admin-white rounded-xl shadow-md p-6 border-l-4 border-admin-light-blue hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Sales</p>
                    <p class="text-2xl font-bold text-admin-blue">$24,589</p>
                    <p class="text-green-600 text-xs mt-2">
                        <i class="fas fa-arrow-up"></i> +12.5%
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-dollar-sign text-admin-light-blue text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Orders Card -->
        <div class="bg-admin-white rounded-xl shadow-md p-6 border-l-4 border-admin-light-blue hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Orders</p>
                    <p class="text-2xl font-bold text-admin-blue">1,847</p>
                    <p class="text-green-600 text-xs mt-2">
                        <i class="fas fa-arrow-up"></i> +8.2%
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-shopping-cart text-admin-light-blue text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Products Card -->
        <div class="bg-admin-white rounded-xl shadow-md p-6 border-l-4 border-admin-light-blue hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Products</p>
                    <p class="text-2xl font-bold text-admin-blue">342</p>
                    <p class="text-green-600 text-xs mt-2">
                        <i class="fas fa-arrow-up"></i> +4 new
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-couch text-admin-light-blue text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Customers Card -->
        <div class="bg-admin-white rounded-xl shadow-md p-6 border-l-4 border-admin-light-blue hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm">Total Customers</p>
                    <p class="text-2xl font-bold text-admin-blue">2,451</p>
                    <p class="text-green-600 text-xs mt-2">
                        <i class="fas fa-arrow-up"></i> +15.3%
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-users text-admin-light-blue text-xl"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts and Recent Activity Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Sales Chart (Placeholder) -->
        <div class="lg:col-span-2 bg-admin-white rounded-xl shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-admin-blue">Sales Overview</h3>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1 focus:outline-none focus:border-admin-light-blue">
                    <option>Last 7 Days</option>
                    <option>Last 30 Days</option>
                    <option>Last 3 Months</option>
                </select>
            </div>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                <div class="text-center text-gray-400">
                    <i class="fas fa-chart-line text-4xl mb-2"></i>
                    <p>Chart will be integrated here</p>
                    <p class="text-xs">(Chart.js / ApexCharts recommended)</p>
                </div>
            </div>
        </div>
        
        <!-- Recent Orders -->
        <div class="bg-admin-white rounded-xl shadow-md p-6">
            <h3 class="text-lg font-semibold text-admin-blue mb-4">Recent Orders</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between border-b pb-3">
                    <div>
                        <p class="font-medium text-gray-800">#ORD-001</p>
                        <p class="text-xs text-gray-500">John Doe</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-admin-blue">$249.99</p>
                        <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full">Delivered</span>
                    </div>
                </div>
                <div class="flex items-center justify-between border-b pb-3">
                    <div>
                        <p class="font-medium text-gray-800">#ORD-002</p>
                        <p class="text-xs text-gray-500">Sarah Smith</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-admin-blue">$459.00</p>
                        <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded-full">Processing</span>
                    </div>
                </div>
                <div class="flex items-center justify-between border-b pb-3">
                    <div>
                        <p class="font-medium text-gray-800">#ORD-003</p>
                        <p class="text-xs text-gray-500">Mike Johnson</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-admin-blue">$129.50</p>
                        <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Shipped</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800">#ORD-004</p>
                        <p class="text-xs text-gray-500">Emma Wilson</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-admin-blue">$899.99</p>
                        <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full">Pending</span>
                    </div>
                </div>
            </div>
            <a href="#" class="block text-center text-admin-light-blue hover:text-admin-blue text-sm mt-4">View All Orders →</a>
        </div>
    </div>
    
    <!-- Top Products Table -->
    <div class="bg-admin-white rounded-xl shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-admin-blue">Top Selling Products</h3>
            <a href="#" class="text-admin-light-blue hover:text-admin-blue text-sm">Manage Products →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Units Sold</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-chair text-admin-light-blue"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Ergonomic Office Chair</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Furniture</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$249.99</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">234</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-admin-blue">$58,497.66</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-bed text-admin-light-blue"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Wooden Bed Frame</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Bedroom</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$599.00</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">156</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-admin-blue">$93,444.00</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-table text-admin-light-blue"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">Modern Coffee Table</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Living Room</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$189.99</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">198</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-admin-blue">$37,618.02</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection