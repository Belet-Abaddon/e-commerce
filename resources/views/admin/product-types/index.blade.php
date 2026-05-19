@extends('layouts.admin')

@section('title', 'Product Type Management')
@section('header', 'Product Type Management')

@section('content')
<div class="space-y-6">
    <!-- Actions Bar -->
    <div class="bg-white rounded-xl shadow-md p-4">
        <div class="flex flex-col sm:flex-row justify-between gap-4">
            <!-- Search -->
            <div class="flex-1">
                <form method="GET" action="{{ route('admin.product-types.index') }}" class="flex gap-2">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search product types..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-search"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.product-types.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    @endif
                </form>
            </div>
            
            <!-- Add Product Type Button -->
            <a href="{{ route('admin.product-types.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New Product Type
            </a>
        </div>
    </div>
    
    <!-- Product Types Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('admin.product-types.index', array_merge(request()->query(), ['sort' => 'name', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-gray-700">
                                Name
                                @if(request('sort') == 'name')
                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="{{ route('admin.product-types.index', array_merge(request()->query(), ['sort' => 'created_at', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}">
                                Created
                                @if(request('sort') == 'created_at')
                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }} ml-1"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($productTypes as $productType)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-tag text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $productType->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 max-w-md">
                                {{ Str::limit($productType->description, 100) ?: '—' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $productType->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $productType->products->count() }} Products
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('admin.product-types.show', $productType) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors" 
                                   title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.product-types.edit', $productType) }}" 
                                   class="text-yellow-600 hover:text-yellow-900 transition-colors" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        onclick="confirmDelete({{ $productType->id }})" 
                                        class="text-red-600 hover:text-red-900 transition-colors"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Individual Delete Form -->
                            <form id="delete-form-{{ $productType->id }}" action="{{ route('admin.product-types.destroy', $productType) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <i class="fas fa-box-open text-5xl mb-3 block"></i>
                            <p class="text-lg">No product types found</p>
                            <a href="{{ route('admin.product-types.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                Create your first product type →
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $productTypes->links() }}
        </div>
    </div>
</div>

<script>
function confirmDelete(productTypeId) {
    if (confirm('Are you sure you want to delete this product type? This action cannot be undone.')) {
        document.getElementById(`delete-form-${productTypeId}`).submit();
    }
}
</script>
@endsection