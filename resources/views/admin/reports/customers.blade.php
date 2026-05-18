@extends('layouts.admin')

@section('title', 'Customers Report - HomeNest Furniture')
@section('header', 'Customers Report')

@section('content')
<div class="space-y-6">
    <div class="flex justify-end space-x-2">
        <button onclick="exportToPDF()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700"><i class="fas fa-file-pdf mr-2"></i> Export PDF</button>
        <button onclick="exportToImage()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><i class="fas fa-image mr-2"></i> Export Image</button>
    </div>

    <div id="reportContent">
        <div class="text-center mb-6 pb-4 border-b"><h2 class="text-2xl font-bold text-admin-blue">Customers Report</h2><p class="text-gray-500">Generated on {{ now()->format('F d, Y') }}</p></div>

        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-4 text-white"><p class="text-sm opacity-90">Total Customers</p><p class="text-2xl font-bold">{{ number_format($totalCustomers) }}</p></div>
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-4 text-white"><p class="text-sm opacity-90">Regular Users</p><p class="text-2xl font-bold">{{ number_format($totalUsers) }}</p></div>
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-4 text-white"><p class="text-sm opacity-90">Administrators</p><p class="text-2xl font-bold">{{ number_format($totalAdmins) }}</p></div>
        </div>

        @if(count($registrationLabels) > 0)
        <div class="bg-white rounded-xl shadow-sm p-5 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-2">Customer Registration Trend</h3>
            <p class="text-sm text-gray-500 mb-4">Monthly new customer signups. Shows growth in customer base over time.</p>
            <canvas id="registrationChart" height="200" style="max-height: 250px;"></canvas>
        </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 bg-gray-50 border-b"><h3 class="font-semibold text-gray-800">All Customers</h3></div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead><tr><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orders</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Spent</th><th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th></tr></thead>
                    <tbody>@foreach($customers as $customer)<tr class="hover:bg-gray-50"><td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $customer->name }}</td><td class="px-6 py-4 text-sm text-gray-600">{{ $customer->email }}</td><td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{ $customer->role == 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700' }}">{{ $customer->role }}</span></td><td class="px-6 py-4 text-sm">{{ $customer->total_orders }}</td><td class="px-6 py-4 text-sm font-semibold text-green-600">${{ number_format($customer->total_spent, 2) }}</td><td class="px-6 py-4 text-sm">{{ $customer->created_at->format('Y-m-d') }}</td></tr>@endforeach</tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t">{{ $customers->links() }}</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<script>
    new Chart(document.getElementById('registrationChart'), { type: 'line', data: { labels: {!! json_encode($registrationLabels) !!}, datasets: [{ label: 'New Customers', data: {!! json_encode($registrationData) !!}, borderColor: '#22c55e', backgroundColor: 'rgba(34, 197, 94, 0.1)', fill: true, tension: 0.4 }] }, options: { responsive: true, maintainAspectRatio: true } });

    async function exportToImage() { const element = document.getElementById('reportContent'); const btn = event.target; const originalText = btn.innerHTML; btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Exporting...'; btn.disabled = true; try { const canvas = await html2canvas(element, { scale: 2, backgroundColor: '#ffffff' }); const link = document.createElement('a'); link.download = 'customers-report-{{ date('Y-m-d') }}.png'; link.href = canvas.toDataURL(); link.click(); } catch (error) { alert('Error: ' + error); } finally { btn.innerHTML = originalText; btn.disabled = false; } }
    async function exportToPDF() { const { jsPDF } = window.jspdf; const element = document.getElementById('reportContent'); const btn = event.target; const originalText = btn.innerHTML; btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Exporting...'; btn.disabled = true; try { const canvas = await html2canvas(element, { scale: 2, backgroundColor: '#ffffff' }); const imgData = canvas.toDataURL('image/png'); const pdf = new jsPDF('p', 'mm', 'a4'); const imgWidth = 210; const imgHeight = (canvas.height * imgWidth) / canvas.width; pdf.addImage(imgData, 'PNG', 0, 0, imgWidth, imgHeight); pdf.save('customers-report-{{ date('Y-m-d') }}.pdf'); } catch (error) { alert('Error: ' + error); } finally { btn.innerHTML = originalText; btn.disabled = false; } }
</script>
@endsection