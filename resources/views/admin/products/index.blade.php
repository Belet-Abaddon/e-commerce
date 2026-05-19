@extends('layouts.admin')

@section('title', 'Product Management')
@section('header', 'Product Management')

@section('content')
    <div class="space-y-6">
        <!-- Filters and Actions Bar -->
        <div class="bg-white rounded-xl shadow-md p-4">
            <form method="GET" action="{{ route('admin.products.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search products..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Brand Filter -->
                    <div>
                        <select name="brand_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Brands</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ request('brand_id') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Product Type Filter -->
                    <div>
                        <select name="product_type_id"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Types</option>
                            @foreach ($productTypes as $type)
                                <option value="{{ $type->id }}"
                                    {{ request('product_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter - Updated with Out of Stock -->
                    <div>
                        <select name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                            <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Out of
                                Stock</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        @if (request()->anyFilled(['search', 'brand_id', 'product_type_id', 'status']))
                            <a href="{{ route('admin.products.index') }}"
                                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        @endif
                    </div>

                    <a href="{{ route('admin.products.create') }}"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 inline-flex items-center gap-2">
                        <i class="fas fa-plus"></i> Add Product
                    </a>
                </div>
            </form>
        </div>

        <!-- Success/Error Messages -->
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

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-circle mr-3 text-red-500 mt-0.5"></i>
                    <div>
                        <p class="font-bold">Please fix the following errors:</p>
                        <ul class="list-disc list-inside mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Products Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>

                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <a
                                    href="{{ route('admin.products.index', array_merge(request()->query(), ['sort' => 'name', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}">
                                    Product Name
                                    @if (request('sort') == 'name')
                                        <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Brand</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                <a
                                    href="{{ route('admin.products.index', array_merge(request()->query(), ['sort' => 'price', 'order' => request('order') == 'asc' ? 'desc' : 'asc'])) }}">
                                    Price
                                    @if (request('sort') == 'price')
                                        <i class="fas fa-sort-{{ request('order') == 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50">

                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::limit($product->description, 50) }}</div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $product->brand->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $product->productType->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-sm font-semibold text-blue-600">
                                    ${{ number_format($product->price, 2) }}
                                </td>
                                <td class="px-4 py-3">
                                    <button onclick="toggleStatus({{ $product->id }})"
                                        class="relative inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors
                                @if ($product->status == 'active') bg-green-100 text-green-700 hover:bg-green-200
                                @elseif($product->status == 'inactive') bg-red-100 text-red-700 hover:bg-red-200
                                @else bg-yellow-100 text-yellow-700 hover:bg-yellow-200 @endif">
                                        <i
                                            class="fas fa-{{ $product->status == 'active' ? 'check-circle' : ($product->status == 'inactive' ? 'times-circle' : 'exclamation-circle') }} mr-1"></i>
                                        {{ $product->status == 'out_of_stock' ? 'Out of Stock' : ucfirst($product->status) }}
                                    </button>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                            class="text-blue-600 hover:text-blue-900" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" onclick="confirmDelete({{ $product->id }})"
                                            class="text-red-600 hover:text-red-900" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    <form id="delete-form-{{ $product->id }}"
                                        action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                        class="hidden">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-gray-500">
                                    <i class="fas fa-box-open text-5xl mb-3 block"></i>
                                    <p class="text-lg">No products found</p>
                                    <a href="{{ route('admin.products.create') }}"
                                        class="text-blue-600 hover:text-blue-800 mt-2 inline-block">
                                        Add your first product →
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $products->links() }}
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(productId) {
            if (confirm('Are you sure you want to delete this product? This will also delete all associated images.')) {
                document.getElementById(`delete-form-${productId}`).submit();
            }
        }

        function toggleStatus(productId) {
            fetch(`/admin/products/${productId}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>
@endsection
