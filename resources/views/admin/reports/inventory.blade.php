@extends('layouts.admin')

@section('title', 'Inventory Report - HomeNest Furniture')
@section('header', 'Inventory Report')

@section('content')
<div class="space-y-6">
    <div class="flex justify-end space-x-2">
        <button onclick="exportToPDF()" class="px-4 py-2 bg-green-600 text-white rounded-lg"><i class="fas fa-file-pdf mr-2"></i> Export PDF</button>
        <button onclick="exportToImage()" class="px-4 py-2 bg-blue-600 text-white rounded-lg"><i class="fas fa-image mr-2"></i> Export Image</button>
    </div>

    <div id="reportContent">
        <div class="text-center mb-6 pb-4 border-b"><h2 class="text-2xl font-bold text-admin-blue">Inventory Report</h2><p class="text-gray-500">Generated on {{ now()->format('F d, Y') }}</p></div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white"><p class="text-sm opacity-90">Total Products</p><p class="text-2xl font-bold">{{ number_format($totalProducts) }}</p></div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white"><p class="text-sm opacity-90">Active Products</p><p class="text-2xl font-bold">{{ number_format($activeProducts) }}</p><p class="text-xs">{{ round(($activeProducts/$totalProducts)*100) }}% in stock</p></div>
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-4 text-white"><p class="text-sm opacity-90">Inactive Products</p><p class="text-2xl font-bold">{{ number_format($inactiveProducts) }}</p></div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white"><p class="text-sm opacity-90">Inventory Value</p><p class="text-2xl font-bold">${{ number_format($totalInventoryValue, 2) }}</p></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Products by Category</h3>
                <p class="text-sm text-gray-500 mb-4">Distribution of products across categories.</p>
                @if(count($categoryLabels) > 0)<canvas id="categoryChart" height="200"></canvas>@endif
                <div class="mt-3 grid grid-cols-2 gap-2 text-xs">@foreach($productsByType as $type)<div><span class="inline-block w-2 h-2 rounded-full mr-1 bg-blue-500"></span> {{ $type->name }}: {{ $type->products_count }}</div>@endforeach</div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-5">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Products by Brand</h3>
                <p class="text-sm text-gray-500 mb-4">Distribution of products across brands.</p>
                @if(count($brandLabels) > 0)<canvas id="brandChart" height="200"></canvas>@endif
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b"><h3 class="font-semibold text-gray-800">All Products</h3></div>
            <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200"><thead><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Brand</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th></tr></thead><tbody>@foreach($products as $product)<tr><td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $product->name }}</td><td class="px-6 py-4 text-sm text-gray-600">{{ $product->productType->name ?? 'N/A' }}</td><td class="px-6 py-4 text-sm text-gray-600">{{ $product->brand->name ?? 'N/A' }}</td><td class="px-6 py-4 text-sm">${{ number_format($product->price, 2) }}</td><td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{ $product->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ ucfirst($product->status) }}</span></td></tr>@endforeach</tbody>}</div>
            <div class="px-6 py-4 border-t">{{ $products->links() }}</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    new Chart(document.getElementById('categoryChart'), { type: 'pie', data: { labels: {!! json_encode($categoryLabels) !!}, datasets: [{ data: {!! json_encode($categoryData) !!}, backgroundColor: ['#3b82f6', '#22c55e', '#eab308', '#ef4444', '#8b5cf6'] }] }, options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom' } } } });
    new Chart(document.getElementById('brandChart'), { type: 'bar', data: { labels: {!! json_encode($brandLabels) !!}, datasets: [{ label: 'Products Count', data: {!! json_encode($brandData) !!}, backgroundColor: '#3b82f6', borderRadius: 6 }] }, options: { responsive: true, maintainAspectRatio: true } });

    async function exportToImage() { const element = document.getElementById('reportContent'); const btn = event.target; const originalText = btn.innerHTML; btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Exporting...'; btn.disabled = true; try { const canvas = await html2canvas(element, { scale: 2, backgroundColor: '#ffffff' }); const link = document.createElement('a'); link.download = 'inventory-report-{{ date('Y-m-d') }}.png'; link.href = canvas.toDataURL(); link.click(); } catch (error) { alert('Error: ' + error); } finally { btn.innerHTML = originalText; btn.disabled = false; } }
    async function exportToPDF() { const { jsPDF } = window.jspdf; const element = document.getElementById('reportContent'); const btn = event.target; const originalText = btn.innerHTML; btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Exporting...'; btn.disabled = true; try { const canvas = await html2canvas(element, { scale: 2, backgroundColor: '#ffffff' }); const imgData = canvas.toDataURL('image/png'); const pdf = new jsPDF('p', 'mm', 'a4'); const imgWidth = 210; const imgHeight = (canvas.height * imgWidth) / canvas.width; pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight); pdf.save('inventory-report-{{ date('Y-m-d') }}.pdf'); } catch (error) { alert('Error: ' + error); } finally { btn.innerHTML = originalText; btn.disabled = false; } }
</script>
@endsection