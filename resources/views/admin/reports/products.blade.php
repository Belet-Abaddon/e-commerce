@extends('layouts.admin')

@section('title', 'Products Report - HomeNest Furniture')
@section('header', 'Products Report')

@section('content')
<div class="space-y-6">
    <!-- Export Buttons -->
    <div class="flex justify-end space-x-2">
        <button onclick="exportToPDF()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
            <i class="fas fa-file-pdf mr-2"></i> Export PDF
        </button>
        <button onclick="exportToImage()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fas fa-image mr-2"></i> Export Image
        </button>
    </div>

    <!-- Report Content -->
    <div id="reportContent">
        <!-- Header -->
        <div class="text-center mb-6 pb-4 border-b">
            <h2 class="text-2xl font-bold text-admin-blue">Products Report</h2>
            <p class="text-gray-500">Generated on {{ now()->format('F d, Y') }}</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <p class="text-sm opacity-90">Total Products</p>
                <p class="text-2xl font-bold">{{ number_format($totalProducts) }}</p>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                <p class="text-sm opacity-90">Active</p>
                <p class="text-2xl font-bold">{{ number_format($activeProducts) }}</p>
                <p class="text-xs">{{ $totalProducts > 0 ? round(($activeProducts/$totalProducts)*100) : 0 }}% of total</p>
            </div>
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-4 text-white">
                <p class="text-sm opacity-90">Inactive</p>
                <p class="text-2xl font-bold">{{ number_format($inactiveProducts) }}</p>
                <p class="text-xs">{{ $totalProducts > 0 ? round(($inactiveProducts/$totalProducts)*100) : 0 }}% of total</p>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
                <p class="text-sm opacity-90">Out of Stock</p>
                <p class="text-2xl font-bold">{{ number_format($outOfStockProducts) }}</p>
                <p class="text-xs">{{ $totalProducts > 0 ? round(($outOfStockProducts/$totalProducts)*100) : 0 }}% of total</p>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                <p class="text-sm opacity-90">Inventory Value</p>
                <p class="text-2xl font-bold">${{ number_format($totalStockValue, 2) }}</p>
            </div>
        </div>

        <!-- Status Distribution Chart -->
        <div class="bg-white rounded-xl shadow-sm p-5 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Product Status Distribution</h3>
                    <p class="text-sm text-gray-500 mt-1">Shows the current status of all products in inventory.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div>
                    <canvas id="statusChart" height="200" style="max-height: 220px;"></canvas>
                </div>
                <div class="flex flex-col justify-center">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-green-500 mr-2"></span>
                                <span class="text-sm font-medium text-gray-700">Active</span>
                            </div>
                            <span class="text-lg font-bold text-green-600">{{ number_format($activeProducts) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-red-500 mr-2"></span>
                                <span class="text-sm font-medium text-gray-700">Inactive</span>
                            </div>
                            <span class="text-lg font-bold text-red-600">{{ number_format($inactiveProducts) }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                            <div class="flex items-center">
                                <span class="w-3 h-3 rounded-full bg-yellow-500 mr-2"></span>
                                <span class="text-sm font-medium text-gray-700">Out of Stock</span>
                            </div>
                            <span class="text-lg font-bold text-yellow-600">{{ number_format($outOfStockProducts) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Products Chart -->
        @if(count($topProductsLabels) > 0 && count($topProductsData) > 0)
        <div class="bg-white rounded-xl shadow-sm p-5 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Top 5 Best Selling Products</h3>
                    <p class="text-sm text-gray-500 mt-1">Products with highest units sold. Focus on these for inventory planning and marketing campaigns.</p>
                </div>
                <div class="text-right">
                    <span class="text-xs text-gray-400">Best Seller: {{ $topProductsLabels[0] ?? 'N/A' }}</span>
                </div>
            </div>
            <canvas id="topProductsChart" height="200" style="max-height: 250px;"></canvas>
            <div class="mt-3 text-center text-xs text-gray-400">X-Axis: Products | Y-Axis: Units Sold</div>
        </div>
        @else
        <div class="bg-white rounded-xl shadow-sm p-5 mb-6 text-center text-gray-500">
            <i class="fas fa-chart-bar text-4xl mb-2"></i>
            <p>No sales data available for products</p>
            <p class="text-sm mt-1">Products will appear here once orders are placed</p>
        </div>
        @endif

        <!-- Top Products Table -->
        @if($topProducts->count() > 0)
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h3 class="font-semibold text-gray-800">Top Selling Products Details</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rank</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Brand</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Units Sold</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($topProducts as $index => $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $index + 1 }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $product->productType->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $product->brand->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-blue-600">{{ number_format($product->total_sold) }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-green-600">${{ number_format($product->total_sold * $product->price, 2) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($product->status == 'active') bg-green-100 text-green-700
                                    @elseif($product->status == 'inactive') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- All Products Table with Pagination -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h3 class="font-semibold text-gray-800">All Products Inventory</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Brand</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Units Sold</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $product->productType->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $product->brand->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">${{ number_format($product->price, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ number_format($product->total_sold) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full 
                                    @if($product->status == 'active') bg-green-100 text-green-700
                                    @elseif($product->status == 'inactive') bg-red-100 text-red-700
                                    @else bg-yellow-100 text-yellow-700
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $product->status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-box-open text-4xl mb-2"></i>
                                <p>No products found</p>
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
                        Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
                    </div>
                    <div>
                        {{ $products->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    // Status Distribution Pie Chart
    @if($totalProducts > 0)
    new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: ['Active', 'Inactive', 'Out of Stock'],
            datasets: [{
                data: [{{ $activeProducts }}, {{ $inactiveProducts }}, {{ $outOfStockProducts }}],
                backgroundColor: ['#22c55e', '#ef4444', '#eab308'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } },
                tooltip: { callbacks: { label: function(ctx) { return ctx.raw + ' products (' + ((ctx.raw / {{ $totalProducts }}) * 100).toFixed(1) + '%)'; } } }
            }
        }
    });
    @endif

    @if(count($topProductsLabels) > 0 && count($topProductsData) > 0)
    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($topProductsLabels) !!},
            datasets: [{
                label: 'Units Sold',
                data: {!! json_encode($topProductsData) !!},
                backgroundColor: '#3b82f6',
                borderRadius: 6,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top', labels: { boxWidth: 12, font: { size: 11 } } },
                tooltip: { callbacks: { label: function(ctx) { return ctx.raw + ' units sold'; } } }
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { stepSize: 1, font: { size: 10 } },
                    title: { display: true, text: 'Units Sold', font: { size: 10 } }
                },
                x: { 
                    ticks: { font: { size: 10 } },
                    title: { display: true, text: 'Products', font: { size: 10 } }
                }
            }
        }
    });
    @endif

    async function exportToImage() {
        const element = document.getElementById('reportContent');
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Exporting...';
        btn.disabled = true;
        try {
            const canvas = await html2canvas(element, { scale: 2, backgroundColor: '#ffffff' });
            const link = document.createElement('a');
            link.download = 'products-report-{{ date('Y-m-d') }}.png';
            link.href = canvas.toDataURL();
            link.click();
        } catch (error) {
            alert('Error exporting image: ' + error);
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }

    async function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const element = document.getElementById('reportContent');
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Exporting...';
        btn.disabled = true;
        try {
            const canvas = await html2canvas(element, { scale: 2, backgroundColor: '#ffffff' });
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF('p', 'mm', 'a4');
            const imgWidth = 210;
            const imgHeight = (canvas.height * imgWidth) / canvas.width;
            pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight);
            pdf.save('products-report-{{ date('Y-m-d') }}.pdf');
        } catch (error) {
            alert('Error exporting PDF: ' + error);
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
</script>
@endsection