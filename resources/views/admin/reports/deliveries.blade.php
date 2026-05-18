@extends('layouts.admin')

@section('title', 'Delivery Report - HomeNest Furniture')
@section('header', 'Delivery Report')

@section('content')
<div class="space-y-6">
    <div class="flex justify-end space-x-2">
        <button onclick="exportToPDF()" class="px-4 py-2 bg-green-600 text-white rounded-lg"><i class="fas fa-file-pdf mr-2"></i> Export PDF</button>
        <button onclick="exportToImage()" class="px-4 py-2 bg-blue-600 text-white rounded-lg"><i class="fas fa-image mr-2"></i> Export Image</button>
    </div>

    <div id="reportContent">
        <div class="text-center mb-6 pb-4 border-b"><h2 class="text-2xl font-bold text-admin-blue">Delivery Report</h2><p class="text-gray-500">Generated on {{ now()->format('F d, Y') }}</p></div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white"><p class="text-sm opacity-90">Total Deliveries</p><p class="text-2xl font-bold">{{ number_format($totalDeliveries) }}</p></div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white"><p class="text-sm opacity-90">Completed</p><p class="text-2xl font-bold">{{ number_format($completedDeliveries) }}</p><p class="text-xs">{{ round($completionRate,1) }}% rate</p></div>
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white"><p class="text-sm opacity-90">In Progress</p><p class="text-2xl font-bold">{{ number_format($pendingDeliveries) }}</p></div>
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg p-4 text-white"><p class="text-sm opacity-90">Cancelled</p><p class="text-2xl font-bold">{{ $statusCounts['Cancelled'] ?? 0 }}</p></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-5 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Delivery Status Distribution</h3>
            <p class="text-sm text-gray-500 mb-4">Shows current status of all deliveries. Helps identify bottlenecks.</p>
            @if(count($statusLabels) > 0)<canvas id="statusChart" height="200" style="max-height: 250px;"></canvas>@endif
            <div class="mt-3 grid grid-cols-5 gap-2 text-center text-xs">
                <div><span class="inline-block w-2 h-2 rounded-full bg-gray-400 mr-1"></span> Pending: {{ $statusCounts['Pending'] ?? 0 }}</div>
                <div><span class="inline-block w-2 h-2 rounded-full bg-yellow-400 mr-1"></span> Processing: {{ $statusCounts['Processing'] ?? 0 }}</div>
                <div><span class="inline-block w-2 h-2 rounded-full bg-blue-400 mr-1"></span> Shipped: {{ $statusCounts['Shipped'] ?? 0 }}</div>
                <div><span class="inline-block w-2 h-2 rounded-full bg-green-400 mr-1"></span> Delivered: {{ $statusCounts['Delivered'] ?? 0 }}</div>
                <div><span class="inline-block w-2 h-2 rounded-full bg-red-400 mr-1"></span> Cancelled: {{ $statusCounts['Cancelled'] ?? 0 }}</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b"><h3 class="font-semibold text-gray-800">Recent Deliveries</h3></div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery ID</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th></tr></thead>
                    <tbody>@foreach($deliveries as $delivery)<tr><td class="px-6 py-4 text-sm">{{ $delivery->id }}</td><td class="px-6 py-4 text-sm font-medium">#{{ str_pad($delivery->order_id, 6, '0', STR_PAD_LEFT) }}</td><td class="px-6 py-4 text-sm">{{ $delivery->order->user->name ?? 'N/A' }}</td><td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full @if($delivery->delivery_status == 'Delivered') bg-green-100 text-green-700 @elseif($delivery->delivery_status == 'Processing') bg-yellow-100 text-yellow-700 @elseif($delivery->delivery_status == 'Shipped') bg-blue-100 text-blue-700 @elseif($delivery->delivery_status == 'Cancelled') bg-red-100 text-red-700 @else bg-gray-100 text-gray-700 @endif">{{ $delivery->delivery_status }}</span></td><td class="px-6 py-4 text-sm">{{ $delivery->created_at->format('Y-m-d') }}</td></tr>@endforeach</tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t">{{ $deliveries->links() }}</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    new Chart(document.getElementById('statusChart'), { type: 'doughnut', data: { labels: {!! json_encode($statusLabels) !!}, datasets: [{ data: {!! json_encode($statusData) !!}, backgroundColor: ['#6b7280', '#eab308', '#3b82f6', '#22c55e', '#ef4444'] }] }, options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'bottom' } } } });

    async function exportToImage() { const element = document.getElementById('reportContent'); const btn = event.target; const originalText = btn.innerHTML; btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Exporting...'; btn.disabled = true; try { const canvas = await html2canvas(element, { scale: 2, backgroundColor: '#ffffff' }); const link = document.createElement('a'); link.download = 'delivery-report-{{ date('Y-m-d') }}.png'; link.href = canvas.toDataURL(); link.click(); } catch (error) { alert('Error: ' + error); } finally { btn.innerHTML = originalText; btn.disabled = false; } }
    async function exportToPDF() { const { jsPDF } = window.jspdf; const element = document.getElementById('reportContent'); const btn = event.target; const originalText = btn.innerHTML; btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Exporting...'; btn.disabled = true; try { const canvas = await html2canvas(element, { scale: 2, backgroundColor: '#ffffff' }); const imgData = canvas.toDataURL('image/png'); const pdf = new jsPDF('p', 'mm', 'a4'); const imgWidth = 210; const imgHeight = (canvas.height * imgWidth) / canvas.width; pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight); pdf.save('delivery-report-{{ date('Y-m-d') }}.pdf'); } catch (error) { alert('Error: ' + error); } finally { btn.innerHTML = originalText; btn.disabled = false; } }
</script>
@endsection