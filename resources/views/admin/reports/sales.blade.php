@extends('layouts.admin')

@section('title', 'Sales Report - HomeNest Furniture')
@section('header', 'Sales Report')

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

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form method="GET" action="{{ route('admin.reports.sales') }}" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" class="px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" class="px-3 py-2 border border-gray-300 rounded-lg">
            </div>
            <div>
                <button type="submit" class="px-4 py-2 bg-admin-blue text-white rounded-lg hover:bg-admin-light-blue">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.reports.sales') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 ml-2">
                    <i class="fas fa-undo mr-2"></i> Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Report Content -->
    <div id="reportContent">
        <!-- Header -->
        <div class="text-center mb-6 pb-4 border-b">
            <h2 class="text-2xl font-bold text-admin-blue">Sales Report</h2>
            <p class="text-gray-500">Generated on {{ now()->format('F d, Y') }}</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                <p class="text-sm opacity-90">Total Orders</p>
                <p class="text-2xl font-bold">{{ number_format($totalOrders) }}</p>
            </div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white">
                <p class="text-sm opacity-90">Total Revenue</p>
                <p class="text-2xl font-bold">${{ number_format($totalRevenue, 2) }}</p>
            </div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white">
                <p class="text-sm opacity-90">Items Sold</p>
                <p class="text-2xl font-bold">{{ number_format($totalItems) }}</p>
            </div>
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-4 text-white">
                <p class="text-sm opacity-90">Avg Order Value</p>
                <p class="text-2xl font-bold">${{ number_format($averageOrderValue, 2) }}</p>
            </div>
        </div>

        <!-- Sales Chart -->
        @if(count($chartLabels) > 0)
        <div class="bg-white rounded-xl shadow-sm p-5 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Monthly Sales Trend</h3>
            <p class="text-sm text-gray-500 mb-4">Shows revenue trends over time, helping identify peak seasons and growth patterns.</p>
            <canvas id="salesChart" height="200" style="max-height: 250px;"></canvas>
            <div class="mt-3 text-center text-xs text-gray-400">X-Axis: Months | Y-Axis: Revenue ($)</div>
        </div>
        @endif

        <!-- Orders Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b">
                <h3 class="font-semibold text-gray-800">Recent Orders</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($orders as $order)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->order_date }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $order->qty }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-green-600">${{ number_format($order->order_total, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">No orders found</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t">{{ $orders->links() }}</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    @if(count($chartLabels) > 0)
    new Chart(document.getElementById('salesChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($chartLabels) !!},
            datasets: [{ label: 'Sales ($)', data: {!! json_encode($chartData) !!}, borderColor: '#3b82f6', backgroundColor: 'rgba(59, 130, 246, 0.1)', tension: 0.4, fill: true }]
        },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'top' } }, scales: { y: { ticks: { callback: v => '$' + v.toLocaleString() } } } }
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
            link.download = 'sales-report-{{ date('Y-m-d') }}.png';
            link.href = canvas.toDataURL();
            link.click();
        } catch (error) { alert('Error: ' + error); }
        finally { btn.innerHTML = originalText; btn.disabled = false; }
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
            pdf.save('sales-report-{{ date('Y-m-d') }}.pdf');
        } catch (error) { alert('Error: ' + error); }
        finally { btn.innerHTML = originalText; btn.disabled = false; }
    }
</script>
@endsection