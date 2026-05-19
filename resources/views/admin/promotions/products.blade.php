@extends('layouts.admin')

@section('title', 'Promotion Products - ' . $promotion->promotion_name)
@section('header', 'Promotion Products: ' . $promotion->promotion_name)

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
        </div>
    </div>

    <!-- Promotion Info Card -->
    <div class="bg-gradient-to-r from-admin-blue to-admin-light-blue rounded-xl shadow-md p-6 text-white">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-sm opacity-90">Promotion Period</p>
                <p class="text-lg font-semibold">{{ \Carbon\Carbon::parse($promotion->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($promotion->end_date)->format('M d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm opacity-90">Total Products</p>
                <p class="text-lg font-semibold">{{ $promotion->products->count() }} products</p>
            </div>
            <div>
                <p class="text-sm opacity-90">Status</p>
                <p class="text-lg font-semibold">
                    @php
                        $today = date('Y-m-d');
                        if ($promotion->end_date < $today) {
                            echo 'Expired';
                        } elseif ($promotion->start_date > $today) {
                            echo 'Upcoming';
                        } else {
                            echo 'Active';
                        }
                    @endphp
                </p>
            </div>
        </div>
    </div>

    <!-- Add Product Form -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-800">Add Product to Promotion</h3>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.promotions.add-product', $promotion->id) }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                    <select name="product_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-admin-light-blue">
                        <option value="">Select Product</option>
                        @foreach($allProducts as $product)
                            @if(!$promotion->products->contains($product->id))
                                <option value="{{ $product->id }}">{{ $product->name }} - ${{ number_format($product->price, 2) }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Discount (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="percentage" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-admin-light-blue">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description (Optional)</label>
                    <input type="text" name="description" placeholder="e.g., Special discount for holidays"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-admin-light-blue">
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-admin-blue text-white rounded-lg hover:bg-admin-light-blue w-full">
                        <i class="fas fa-plus mr-2"></i> Add Product
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($promotion->products as $product)
                    @php
                        $discountedPrice = $product->price - ($product->price * $product->pivot->percentage / 100);
                    @endphp
                    <tr class="hover:bg-gray-50">
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
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                            {{ $product->pivot->description ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <button onclick="openEditModal({{ $promotion->id }}, {{ $product->id }}, {{ $product->pivot->percentage }}, '{{ $product->pivot->description }}')" 
                                    class="text-green-600 hover:text-green-700">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteProduct({{ $promotion->id }}, {{ $product->id }})" 
                                    class="text-red-600 hover:text-red-700">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            No products added to this promotion yet
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Product Modal -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Edit Promotion Product</h3>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Discount (%)</label>
                <input type="number" step="0.01" min="0" max="100" name="percentage" id="editPercentage" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-admin-light-blue">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <input type="text" name="description" id="editDescription"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-admin-light-blue">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-admin-blue text-white rounded-lg hover:bg-admin-light-blue">Update</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function openEditModal(promotionId, productId, percentage, description) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        const percentageInput = document.getElementById('editPercentage');
        const descriptionInput = document.getElementById('editDescription');
        
        form.action = `/admin/promotions/${promotionId}/products/${productId}`;
        percentageInput.value = percentage;
        descriptionInput.value = description || '';
        modal.classList.remove('hidden');
    }
    
    function closeEditModal() {
        const modal = document.getElementById('editModal');
        modal.classList.add('hidden');
    }
    
    function deleteProduct(promotionId, productId) {
        if (confirm('Are you sure you want to remove this product from the promotion?')) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/promotions/${promotionId}/products/${productId}`;
            form.submit();
        }
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target == modal) {
            modal.classList.add('hidden');
        }
    }
</script>
@endsection