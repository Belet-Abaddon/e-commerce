@extends('layouts.admin')

@section('title', 'Promotion Details - HomeNest Furniture')
@section('header', 'Promotion Details')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div class="flex justify-between items-center">
        <a href="{{ route('admin.promotions.index') }}" class="inline-flex items-center text-admin-blue hover:text-admin-light-blue">
            <i class="fas fa-arrow-left mr-2"></i> Back to Promotions
        </a>
        <div class="flex space-x-2">
            <a href="{{ route('admin.promotions.edit', $promotion->id) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-edit mr-2"></i> Edit Promotion
            </a>
            <button onclick="deletePromotion({{ $promotion->id }})" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                <i class="fas fa-trash mr-2"></i> Delete
            </button>
        </div>
    </div>

    <!-- Promotion Information -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">Promotion Information</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">ID</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $promotion->id }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">Promotion Name</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $promotion->promotion_name }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">Start Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($promotion->start_date)->format('Y-m-d') }}</p>
                    </div>
                </div>
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">End Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($promotion->end_date)->format('Y-m-d') }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">Status</label>
                        <p class="mt-1">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($status == 'Active') bg-green-100 text-green-700
                                @elseif($status == 'Upcoming') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ $status }}
                            </span>
                        </p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500">Created At</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $promotion->created_at->format('Y-m-d H:i:s') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products in Promotion -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">Products in Promotion</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Original Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount %</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discounted Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($promotion->products as $product)
                    @php
                        $discountedPrice = $product->price - ($product->price * $product->pivot->percentage / 100);
                    @endphp
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $product->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            ${{ number_format($product->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                            {{ $product->pivot->percentage }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-admin-blue">
                            ${{ number_format($discountedPrice, 2) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $product->pivot->description ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function deletePromotion(id) {
        if (confirm('Are you sure you want to delete this promotion?')) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/promotions/${id}`;
            form.submit();
        }
    }
</script>
@endsection