@extends('layouts.admin')

@section('title', $productType->name)
@section('header', $productType->name)

@section('content')
<div class="space-y-6">
    <!-- Product Type Details Card -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-tag text-blue-600 text-3xl"></i>
                    </div>
                    
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">{{ $productType->name }}</h2>
                        <p class="text-gray-600 mt-1">Created {{ $productType->created_at->format('F d, Y') }}</p>
                        <p class="text-sm text-gray-500">Last updated {{ $productType->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <a href="{{ route('admin.product-types.edit', $productType) }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <button onclick="confirmDelete({{ $productType->id }})" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </div>
            
            @if($productType->description)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Description</h3>
                <div class="prose max-w-none text-gray-700">
                    {{ $productType->description }}
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Products from this Type -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Products in {{ $productType->name }}</h3>
            <p class="text-sm text-gray-600 mt-1">Total: {{ $productType->products->count() }} products</p>
        </div>
        
        @if($productType->products->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Brand</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($productType->products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $product->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $product->brand->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">
                            ${{ number_format($product->price, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full {{ $product->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($product->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="#" class="text-blue-600 hover:text-blue-900">View Product</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-12 text-center text-gray-500">
            <i class="fas fa-box-open text-5xl mb-3 block"></i>
            <p>No products found in this category yet.</p>
            <a href="#" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                Add products to this type →
            </a>
        </div>
        @endif
    </div>
</div>

<form id="delete-form-{{ $productType->id }}" action="{{ route('admin.product-types.destroy', $productType) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete(productTypeId) {
    if (confirm('Are you sure you want to delete this product type? This action cannot be undone.')) {
        document.getElementById(`delete-form-${productTypeId}`).submit();
    }
}
</script>
@endsection