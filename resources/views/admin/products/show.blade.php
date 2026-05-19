@extends('layouts.admin')

@section('title', $product->name)
@section('header', $product->name)

@section('content')
    <div class="space-y-6">
        <!-- Product Details Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-20 h-20 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-couch text-blue-600 text-3xl"></i>
                            </div>
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
                                <div class="flex items-center gap-2 mt-1">
                                    <span
                                        class="text-2xl font-bold text-blue-600">${{ number_format($product->price, 2) }}</span>
                                    <!-- In the show view, update the status display -->
                                    <span
                                        class="px-2 py-1 text-xs rounded-full 
                                            @if ($product->status == 'active') bg-green-100 text-green-700
                                            @elseif($product->status == 'inactive') bg-red-100 text-red-700
                                            @else bg-yellow-100 text-yellow-700 @endif">
                                        {{ $product->status == 'out_of_stock' ? 'Out of Stock' : ucfirst($product->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-600">Brand</p>
                                <p class="font-semibold text-gray-900">{{ $product->brand->name ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-600">Product Type</p>
                                <p class="font-semibold text-gray-900">{{ $product->productType->name ?? 'N/A' }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-600">Created</p>
                                <p class="font-semibold text-gray-900">{{ $product->created_at->format('F d, Y H:i') }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-600">Last Updated</p>
                                <p class="font-semibold text-gray-900">{{ $product->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        @if ($product->description)
                            <div class="border-t border-gray-200 pt-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                                <p class="text-gray-700">{{ $product->description }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}"
                            class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <button onclick="confirmDelete({{ $product->id }})"
                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Images -->
        @if ($product->images->count() > 0)
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Product Images</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($product->images as $image)
                            <div class="relative group">
                                <img src="{{ Storage::url($image->image_path) }}"
                                    class="w-full h-48 object-cover rounded-lg shadow-md">
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <form id="delete-form-{{ $product->id }}" action="{{ route('admin.products.destroy', $product) }}" method="POST"
        class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function confirmDelete(productId) {
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                document.getElementById(`delete-form-${productId}`).submit();
            }
        }
    </script>
@endsection
