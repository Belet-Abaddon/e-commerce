@extends('layouts.admin')

@section('title', 'Feedback Management')
@section('header', 'Customer Feedback')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Customer Feedback</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage and review customer feedback and ratings</p>
                </div>
            </div>
        </div>

        <!-- Success/Error Alert Triggers -->
        @if (session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-500 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm flex items-center">
                <i class="fas fa-exclamation-circle mr-3 text-red-500 text-lg"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Total Feedback</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalFeedbacks }}</p>
                    </div>
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="fas fa-comments text-blue-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Average Rating</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ number_format($averageRating, 1) }}</p>
                    </div>
                    <div class="bg-yellow-100 rounded-full p-3">
                        <i class="fas fa-star text-yellow-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">5 Star Ratings</p>
                        <p class="text-2xl font-bold text-green-600">{{ $ratingDistribution[5] }}</p>
                    </div>
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-smile text-green-600 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600">Response Rate</p>
                        <p class="text-2xl font-bold text-purple-600">{{ $totalFeedbacks > 0 ? 100 : 0 }}%</p>
                    </div>
                    <div class="bg-purple-100 rounded-full p-3">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rating Distribution Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Rating Distribution</h3>
            </div>
            <div class="p-4">
                @foreach([5,4,3,2,1] as $rating)
                    @php
                        $count = $ratingDistribution[$rating];
                        $percentage = $totalFeedbacks > 0 ? ($count / $totalFeedbacks) * 100 : 0;
                    @endphp
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-16 text-sm font-medium text-gray-700">{{ $rating }} Star</div>
                        <div class="flex-1 h-4 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-yellow-400 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <div class="w-16 text-sm text-gray-600">{{ $count }} ({{ round($percentage) }}%)</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
            <div class="p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Filter Feedback</h3>
            </div>
            <div class="p-4">
                <form method="GET" action="{{ route('admin.feedback.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer or feedback..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                        <select name="rating" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Ratings</option>
                            @for($i = 5; $i >= 1; $i--)
                                <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div class="flex items-end gap-2">
                        <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition dynamic shadow-sm">
                            <i class="fas fa-search mr-2"></i>Filter
                        </button>
                        @if(request()->anyFilled(['search', 'rating', 'date_from', 'date_to']))
                            <a href="{{ route('admin.feedback.index') }}" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-center transition">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Feedback Data Grid Grid Matrix Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <form id="bulkDeleteForm" action="{{ route('admin.feedback.bulk-delete') }}" method="POST">
                @csrf
                @method('DELETE')
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 w-12 text-center">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Feedback</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rating</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($feedbacks as $feedback)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $feedback->id }}" class="feedback-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $feedback->user->name ?? 'Deleted User' }}</div>
                                            <div class="text-xs text-gray-500">{{ $feedback->user->email ?? 'No email available' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-md break-words">
                                        {{ Str::limit($feedback->feedback, 100) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star text-xs {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                        @endfor
                                        <span class="ml-2 text-sm text-gray-600">({{ $feedback->rating }})</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $feedback->created_at->format('M d, Y H:i') }}
                                    <br>
                                    <span class="text-xs text-gray-400">{{ $feedback->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-3">
                                        <a href="{{ route('admin.feedback.show', $feedback) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" onclick="confirmDelete({{ $feedback->id }})" class="text-red-600 hover:text-red-900 focus:outline-none" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $feedback->id }}" action="{{ route('admin.feedback.destroy', $feedback) }}" method="POST" class="hidden">
                                        @csrf
                                   
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                    <i class="fas fa-comment-slash text-5xl mb-3 block text-gray-300"></i>
                                    <p class="text-lg font-medium">No customer feedback matching criteria</p>
                                    <p class="text-sm text-gray-400 mt-1">Try modifying your filter parameters or search constraints.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($feedbacks->count() > 0)
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div>
                        <button type="button" id="bulkDeleteBtn" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 transition" disabled>
                            <i class="fas fa-trash"></i> Delete Selected Rows
                        </button>
                    </div>
                    <div>
                        {{ $feedbacks->links() }}
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAll = document.getElementById('selectAll');
    const feedbackCheckboxes = document.querySelectorAll('.feedback-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');

    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.feedback-checkbox:checked').length;
        if (bulkDeleteBtn) {
            bulkDeleteBtn.disabled = (checkedCount === 0);
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            feedbackCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            updateBulkDeleteButton();
        });
    }

    feedbackCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Uncheck global selector box dynamically if any child checkbox gets toggled off
            if (!this.checked && selectAll) {
                selectAll.checked = false;
            }
            // Check global selector box dynamically if all children checkboxes are toggled on
            if (document.querySelectorAll('.feedback-checkbox:checked').length === feedbackCheckboxes.length && selectAll) {
                selectAll.checked = true;
            }
            updateBulkDeleteButton();
        });
    });

    if (bulkDeleteBtn && bulkDeleteForm) {
        bulkDeleteBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const checkedCount = document.querySelectorAll('.feedback-checkbox:checked').length;
            if (checkedCount === 0) return;
            
            if (confirm(`Are you completely sure you want to drop the selected ${checkedCount} feedback record(s)? This database modification is permanent.`)) {
                bulkDeleteForm.submit();
            }
        });
    }
});

// Single Deletion Scope Logic Pipeline Handler
function confirmDelete(feedbackId) {
    if (confirm('Are you sure you want to permanently clean this customer review trace out of your records?')) {
        const structuralForm = document.getElementById(`delete-form-${feedbackId}`);
        if (structuralForm) {
            structuralForm.submit();
        }
    }
}
</script>
@endsection