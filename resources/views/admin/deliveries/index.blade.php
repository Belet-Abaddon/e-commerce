@extends('layouts.admin')

@section('title', 'Deliveries Management')
@section('header', 'Deliveries Management')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Deliveries Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Track and manage delivery status</p>
                </div>
            </div>
        </div>

        <!-- Success/Error Alert Panels -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-3 text-green-500"></i>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Statistics Cards - 3 Statuses -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Deliveries</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalDeliveries }}</p>
                    </div>
                    <div class="bg-gray-100 rounded-full p-3">
                        <i class="fas fa-truck text-gray-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Pending</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $pendingDeliveries }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">In Progress</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $inProgressDeliveries }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="fas fa-spinner fa-pulse text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Delivered</p>
                        <p class="text-2xl font-bold text-green-600">{{ $deliveredDeliveries }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Filter Buttons -->
        <div class="flex gap-3 mb-6">
            <a href="{{ route('admin.deliveries.index') }}" class="px-4 py-2 rounded-lg {{ !request('delivery_status') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                All
            </a>
            <a href="{{ route('admin.deliveries.index', ['delivery_status' => 'pending']) }}" class="px-4 py-2 rounded-lg {{ request('delivery_status') == 'pending' ? 'bg-yellow-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                ⏳ Pending
            </a>
            <a href="{{ route('admin.deliveries.index', ['delivery_status' => 'in_progress']) }}" class="px-4 py-2 rounded-lg {{ request('delivery_status') == 'in_progress' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                🔄 In Progress
            </a>
            <a href="{{ route('admin.deliveries.index', ['delivery_status' => 'delivered']) }}" class="px-4 py-2 rounded-lg {{ request('delivery_status') == 'delivered' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                ✅ Delivered
            </a>
        </div>

        <!-- Filters Form Panel -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Filter Deliveries</h3>
            </div>
            <div class="p-4">
                <form method="GET" action="{{ route('admin.deliveries.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Order ID or customer..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Status</label>
                        <select name="delivery_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('delivery_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ request('delivery_status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="delivered" {{ request('delivery_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition shadow-sm">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                        @if(request()->anyFilled(['search', 'delivery_status', 'date_from', 'date_to']))
                            <a href="{{ route('admin.deliveries.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-center transition">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Deliveries Table Data Area Matrix -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form id="bulkUpdateForm" action="{{ route('admin.deliveries.bulk-update') }}" method="POST">
                @csrf
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 w-12 text-center">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order #</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Address</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Date</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($deliveries as $delivery)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $delivery->id }}" class="delivery-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-gray-900">#{{ $delivery->order_id }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600 text-sm"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $delivery->order->user->name ?? 'Deleted Customer' }}</div>
                                            <div class="text-xs text-gray-500">{{ $delivery->order->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-600 max-w-xs truncate" title="{{ $delivery->order->order_address ?? 'N/A' }}">
                                        {{ $delivery->order->order_address ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select onchange="updateDeliveryStatus({{ $delivery->id }}, this.value)" 
                                            class="px-3 py-1 text-sm rounded-full font-semibold border border-gray-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-500
                                            @if($delivery->delivery_status == 'pending') bg-yellow-100 text-yellow-700
                                            @elseif($delivery->delivery_status == 'in_progress') bg-blue-100 text-blue-700
                                            @else bg-green-100 text-green-700
                                            @endif">
                                        <option value="pending" {{ $delivery->delivery_status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                        <option value="in_progress" {{ $delivery->delivery_status == 'in_progress' ? 'selected' : '' }}>🔄 In Progress</option>
                                        <option value="delivered" {{ $delivery->delivery_status == 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                                    </select>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $delivery->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('admin.deliveries.show', $delivery) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" onclick="showTracking({{ $delivery->id }})" class="text-green-600 hover:text-green-900 focus:outline-none" title="Track Delivery">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-truck text-5xl mb-3 block text-gray-300"></i>
                                    <p class="text-lg font-medium">No deliveries found matching filters</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($deliveries->count() > 0)
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <select id="bulkStatus" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">Bulk Update Status</option>
                            <option value="pending">⏳ Pending</option>
                            <option value="in_progress">🔄 In Progress</option>
                            <option value="delivered">✅ Delivered</option>
                        </select>
                        <button type="button" id="bulkUpdateBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium shadow-sm">
                            Apply to Selected
                        </button>
                    </div>
                    <div>
                        {{ $deliveries->links() }}
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

<!-- Tracking History Timeline Modal -->
<div id="trackingModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50 transition-opacity">
    <div class="relative top-20 mx-auto p-6 border w-96 shadow-xl rounded-xl bg-white animate-fade-in">
        <div class="flex justify-between items-center mb-4 pb-2 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Delivery Tracking History</h3>
            <button onclick="closeTrackingModal()" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="trackingHistory" class="space-y-4 max-h-96 overflow-y-auto pr-1">
            <!-- Asynchronous Data Pipeline Target Container Injection Area -->
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select All functionality
    const selectAll = document.getElementById('selectAll');
    const deliveryCheckboxes = document.querySelectorAll('.delivery-checkbox');
    const bulkUpdateBtn = document.getElementById('bulkUpdateBtn');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            deliveryCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        });
    }

    // Bulk Update Handler Form Pipeline
    if (bulkUpdateBtn) {
        bulkUpdateBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.delivery-checkbox:checked')).map(cb => cb.value);
            const status = document.getElementById('bulkStatus').value;
            
            if (selectedIds.length === 0) {
                alert('Please select at least one delivery item checkpoint.');
                return;
            }
            
            if (!status) {
                alert('Please pick a target status change payload.');
                return;
            }
            
            if (confirm(`Are you sure you want to update ${selectedIds.length} delivery row records to ${status}?`)) {
                const form = document.getElementById('bulkUpdateForm');
                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'delivery_status';
                statusInput.value = status;
                form.appendChild(statusInput);
                form.submit();
            }
        });
    }
});


function updateDeliveryStatus(deliveryId, status) {
    fetch(`/admin/deliveries/${deliveryId}/update-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            _method: 'POST',
            delivery_status: status 
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response connection matrix failure drop.');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showNotification('Delivery tracking milestone state updated successfully!', 'success');
            setTimeout(() => {
                location.reload();
            }, 800);
        } else {
            showNotification('Failed to update delivery milestone state parameters.', 'error');
        }
    })
    .catch(error => {
        console.error('Pipeline Processing Error:', error);
        showNotification('Error updating status context from application server.', 'error');
    });
}

function showTracking(deliveryId) {
    fetch(`/admin/deliveries/${deliveryId}/tracking`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const trackingHtml = data.tracking_history.map((item, index) => `
                    <div class="flex items-start gap-4 pb-4 border-b border-gray-100 last:border-0 last:pb-0">
                        <div class="flex-shrink-0 mt-0.5">
                            <div class="w-8 h-8 ${item.completed ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-400'} rounded-full flex items-center justify-center shadow-sm">
                                <i class="fas ${item.completed ? 'fa-check-circle' : 'fa-circle-notch fa-spin'} text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-900 text-sm">${item.status}</p>
                            <p class="text-xs text-gray-600 mt-0.5 break-words">${item.description}</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-1"><i class="far fa-clock mr-1"></i>${new Date(item.date).toLocaleString()}</p>
                        </div>
                    </div>
                `).join('');
                
                document.getElementById('trackingHistory').innerHTML = trackingHtml;
                document.getElementById('trackingModal').classList.remove('hidden');
            }
        })
        .catch(err => {
            console.error(err);
            showNotification('Could not resolve data pipeline for tracking reference.', 'error');
        });
}

function closeTrackingModal() {
    document.getElementById('trackingModal').classList.add('hidden');
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-24 right-4 RegalAlert z-50 px-6 py-3 rounded-lg shadow-xl text-white ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} transition-all duration-300 transform translate-y-0`;
    notification.innerHTML = `
        <div class="flex items-center gap-2 font-medium text-sm">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
            <span>${message}</span>
        </div>
    `;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 350);
    }, 3000);
}

// Event capture intercept window listener modal drop
document.getElementById('trackingModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTrackingModal();
    }
});
</script>

<style>
    select option {
        background: white;
        color: #1f2937;
    }
</style>
@endsection