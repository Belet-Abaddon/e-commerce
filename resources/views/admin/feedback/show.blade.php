@extends('layouts.admin')

@section('title', 'Feedback Details')
@section('header', 'Feedback Details')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Feedback Details</h1>
                    <p class="text-sm text-gray-600 mt-1">View customer feedback and rating</p>
                </div>
                <a href="{{ route('admin.feedback.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Feedback
                </a>
            </div>
        </div>

        <!-- Feedback Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Customer Info -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $feedback->user->name ?? 'Deleted User' }}</h3>
                            <p class="text-sm text-gray-500">{{ $feedback->user->email ?? 'No email available' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $feedback->created_at->format('F d, Y H:i') }}</p>
                        <p class="text-xs text-gray-400">{{ $feedback->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Rating -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Rating</h4>
                <div class="flex items-center">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star text-2xl {{ $i <= $feedback->rating ? 'text-yellow-400' : 'text-gray-300' }} mr-1"></i>
                    @endfor
                    <span class="ml-3 text-lg font-semibold text-gray-900">{{ $feedback->rating }} / 5</span>
                </div>
            </div>

            <!-- Feedback Content -->
            <div class="px-6 py-4">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Feedback</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-800 leading-relaxed">{{ $feedback->feedback }}</p>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500">Feedback ID</p>
                        <p class="text-sm font-medium text-gray-900">#{{ $feedback->id }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Last Updated</p>
                        <p class="text-sm font-medium text-gray-900">{{ $feedback->updated_at->format('F d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                <button onclick="confirmDelete({{ $feedback->id }})" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition duration-200">
                    <i class="fas fa-trash mr-2"></i>Delete Feedback
                </button>
            </div>
        </div>
    </div>
</div>

<form id="delete-form-{{ $feedback->id }}" action="{{ route('admin.feedback.destroy', $feedback) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete(feedbackId) {
    if (confirm('Are you sure you want to delete this feedback? This action cannot be undone.')) {
        document.getElementById(`delete-form-${feedbackId}`).submit();
    }
}
</script>
@endsection