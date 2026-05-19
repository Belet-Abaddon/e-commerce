@extends('layouts.admin')

@section('title', 'Brand Management')
@section('header', 'Brand Management')

@section('content')
<div class="space-y-6">
    <!-- Actions Bar -->
    <div class="bg-white rounded-xl shadow-md p-4">
        <div class="flex flex-col sm:flex-row justify-between gap-4">
            <!-- Search -->
            <div class="flex-1">
                <form method="GET" action="{{ route('admin.brands.index') }}" class="flex gap-2">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}"
                            placeholder="Search brands..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        <i class="fas fa-search"></i> Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('admin.brands.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    @endif
                </form>
            </div>
            
            <!-- Add Brand Button -->
            <a href="{{ route('admin.brands.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 inline-flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New Brand
            </a>
        </div>
    </div>
    
    <!-- Brands Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Logo</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            <a href="{{ route('admin.brands.index', array_merge(request()->query(), ['sort' => 'name', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}" class="hover:text-gray-700">
                                Name
                                @if(request('sort') == 'name')
                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                            <a href="{{ route('admin.brands.index', array_merge(request()->query(), ['sort' => 'created_at', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}">
                                Created
                                @if(request('sort') == 'created_at')
                                    <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }}"></i>
                                @endif
                            </a>
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Products</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($brands as $brand)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            @if($brand->logo)
                                <img src="{{ Storage::url($brand->logo) }}" alt="{{ $brand->name }}" class="w-12 h-12 object-cover rounded-lg">
                            @else
                                <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400 text-2xl"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="font-medium text-gray-900">{{ $brand->name }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm text-gray-600 max-w-md">
                                {{ Str::limit($brand->description, 100) }}
                            </p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $brand->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $brand->products_count ?? $brand->products->count() }} Products
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.brands.show', $brand) }}" 
                                   class="text-blue-600 hover:text-blue-900" 
                                   title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.brands.edit', $brand) }}" 
                                   class="text-yellow-600 hover:text-yellow-900" 
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        onclick="confirmDelete({{ $brand->id }})" 
                                        class="text-red-600 hover:text-red-900"
                                        title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            
                            <!-- Individual Delete Form -->
                            <form id="delete-form-{{ $brand->id }}" action="{{ route('admin.brands.destroy', $brand) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            <i class="fas fa-box-open text-4xl mb-2 block"></i>
                            <p>No brands found.</p>
                            <a href="{{ route('admin.brands.create') }}" class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                Create your first brand →
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
            {{ $brands->links() }}
        </div>
    </div>
</div>

<script>
// Single Delete Confirmation
function confirmDelete(brandId) {
    if (confirm('Are you sure you want to delete this brand? This action cannot be undone.')) {
        document.getElementById(`delete-form-${brandId}`).submit();
    }
}
</script>
@endsection