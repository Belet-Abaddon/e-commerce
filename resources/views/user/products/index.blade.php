@extends('layouts.app')

@section('title', 'Products - HomeNest')
@section('header', 'Our Products')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-8 mb-8 text-white shadow-sm">
            <h1 class="text-3xl font-bold mb-2">Discover Our Collection</h1>
            <p class="text-blue-100">Find the perfect furniture for your home</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters Area Section Grid -->
            <div class="lg:w-1/4 space-y-6">
                
                <!-- Brand Filter Card Component -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-base font-bold text-gray-900">Brands</h3>
                        @if(request('brand_id'))
                            <button type="button" onclick="clearFilter('brand_id')" class="text-xs text-red-500 font-semibold hover:underline">Clear</button>
                        @endif
                    </div>
                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        @foreach($brands as $brand)
                        <label class="flex items-center cursor-pointer p-1 rounded hover:bg-gray-50 transition">
                            <input type="radio" name="brand_filter" value="{{ $brand->id }}" class="brand-filter text-blue-600 focus:ring-blue-500 mr-3" {{ request('brand_id') == $brand->id ? 'checked' : '' }}>
                            <span class="text-sm {{ request('brand_id') == $brand->id ? 'text-blue-600 font-bold' : 'text-gray-700' }}">{{ $brand->name }}</span>
                            <span class="ml-auto text-xs text-gray-400 font-mono">({{ $brand->products_count }})</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Product Type Filter Component -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-base font-bold text-gray-900">Categories</h3>
                        @if(request('product_type_id'))
                            <button type="button" onclick="clearFilter('product_type_id')" class="text-xs text-red-500 font-semibold hover:underline">Clear</button>
                        @endif
                    </div>
                    <div class="space-y-2 max-h-64 overflow-y-auto pr-1">
                        @foreach($productTypes as $type)
                        <label class="flex items-center cursor-pointer p-1 rounded hover:bg-gray-50 transition">
                            <input type="radio" name="type_filter" value="{{ $type->id }}" class="type-filter text-blue-600 focus:ring-blue-500 mr-3" {{ request('product_type_id') == $type->id ? 'checked' : '' }}>
                            <span class="text-sm {{ request('product_type_id') == $type->id ? 'text-blue-600 font-bold' : 'text-gray-700' }}">{{ $type->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Range Filter Component -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-base font-bold text-gray-900 mb-4 border-b pb-2">Price Range</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <div class="flex-1">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-1">Min ($)</label>
                                <input type="number" id="min_price" placeholder="{{ $minPrice }}" value="{{ request('min_price') }}" class="w-full text-sm px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="flex-1">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-1">Max ($)</label>
                                <input type="number" id="max_price" placeholder="{{ $maxPrice }}" value="{{ request('max_price') }}" class="w-full text-sm px-3 py-1.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                        <button id="applyPriceBtn" class="w-full bg-blue-600 text-white font-bold text-sm py-2 rounded-lg hover:bg-blue-700 transition shadow-sm">
                            Apply Price Range
                        </button>
                    </div>
                </div>

                <!-- Sort Options Component -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-base font-bold text-gray-900 mb-4 border-b pb-2">Sort By</h3>
                    <div class="space-y-2">
                        <label class="flex items-center cursor-pointer p-1 rounded hover:bg-gray-50">
                            <input type="radio" name="sort" value="newest" class="sort-option text-blue-600 focus:ring-blue-500 mr-3" {{ (request('sort') == 'created_at' || !request('sort')) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Newest First</span>
                        </label>
                        <label class="flex items-center cursor-pointer p-1 rounded hover:bg-gray-50">
                            <input type="radio" name="sort" value="price_asc" class="sort-option text-blue-600 focus:ring-blue-500 mr-3" {{ (request('sort') == 'price' && request('order') == 'asc') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Price: Low to High</span>
                        </label>
                        <label class="flex items-center cursor-pointer p-1 rounded hover:bg-gray-50">
                            <input type="radio" name="sort" value="price_desc" class="sort-option text-blue-600 focus:ring-blue-500 mr-3" {{ (request('sort') == 'price' && request('order') == 'desc') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Price: High to Low</span>
                        </label>
                        <label class="flex items-center cursor-pointer p-1 rounded hover:bg-gray-50">
                            <input type="radio" name="sort" value="name_asc" class="sort-option text-blue-600 focus:ring-blue-500 mr-3" {{ (request('sort') == 'name' && request('order') == 'asc') ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700">Name: A to Z</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Main Content Area Right Scope -->
            <div class="lg:w-3/4">
                
                <!-- Global Search Bar Filter Component -->
                <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
                    <form method="GET" action="{{ route('user.products.index') }}" class="flex gap-3">
                        <!-- Forward existing key system filter traces during standard text lookups -->
                        @if(request('brand_id')) <input type="hidden" name="brand_id" value="{{ request('brand_id') }}"> @endif
                        @if(request('product_type_id')) <input type="hidden" name="product_type_id" value="{{ request('product_type_id') }}"> @endif
                        @if(request('min_price')) <input type="hidden" name="min_price" value="{{ request('min_price') }}"> @endif
                        @if(request('max_price')) <input type="hidden" name="max_price" value="{{ request('max_price') }}"> @endif
                        
                        <div class="flex-1">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search catalog items..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 shadow-sm transition">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request()->anyFilled(['search', 'brand_id', 'product_type_id', 'min_price', 'max_price']))
                            <a href="{{ route('user.products.index') }}" class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-600 flex items-center justify-center transition" title="Reset All Filters">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        @endif
                    </form>
                </div>

                <!-- Most Ordered Products Row Carousel Grid Section -->
                @if($mostOrdered && $mostOrdered->count() > 0 && !request()->anyFilled(['search', 'brand_id', 'product_type_id']))
                    <div class="mb-8">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-fire text-orange-500 mr-2 animate-pulse"></i> Most Popular Choices
                        </h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($mostOrdered as $item)
                            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition flex flex-col justify-between">
                                <a href="{{ route('user.products.show', $item->id) }}" class="block bg-gray-50 relative">
                                    @if($item->images->first())
                                        <img src="{{ Storage::url($item->images->first()->image_path) }}" class="w-full h-40 object-cover">
                                    @else
                                        <div class="w-full h-40 flex items-center justify-center">
                                            <i class="fas fa-couch text-gray-300 text-3xl"></i>
                                        </div>
                                    @endif
                                </a>
                                <div class="p-3 flex-1 flex flex-col justify-between">
                                    <div>
                                        <span class="text-[10px] uppercase font-extrabold text-gray-400 block tracking-wider">{{ $item->brand->name ?? 'HomeNest' }}</span>
                                        <h3 class="font-bold text-gray-800 text-xs mt-0.5 line-clamp-1">{{ $item->name }}</h3>
                                    </div>
                                    <div class="mt-2 pt-2 border-t border-gray-50 flex items-center justify-between">
                                        <span class="text-sm font-black text-blue-600">${{ number_format($item->price, 2) }}</span>
                                        <a href="{{ route('user.products.order', $item->id) }}" class="bg-blue-600 text-white px-2.5 py-1 text-xs font-bold rounded hover:bg-blue-700 transition shadow-sm">
                                            Buy
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Standard General Main Catalog Collection View -->
                <div>
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-gray-900">All Collection Items</h2>
                        <p class="text-xs text-gray-400 font-medium font-mono">{{ $products->total() }} matches listed</p>
                    </div>

                    <div id="productsGrid">
                        @include('user.products.partials.product_grid', ['products' => $products])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const currentUrl = new URL(window.location.href);

    // Dynamic brand check selector configurations
    document.querySelectorAll('.brand-filter').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                currentUrl.searchParams.set('brand_id', this.value);
                currentUrl.searchParams.delete('page'); // Flush index offset sequence parameters
                window.location.href = currentUrl.toString();
            }
        });
    });

    // Dynamic type category configuration controls mapping
    document.querySelectorAll('.type-filter').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                currentUrl.searchParams.set('product_type_id', this.value);
                currentUrl.searchParams.delete('page');
                window.location.href = currentUrl.toString();
            }
        });
    });

    // Standard sorting option logic definitions controls update
    document.querySelectorAll('.sort-option').forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                const value = this.value;
                if (value === 'price_asc') {
                    currentUrl.searchParams.set('sort', 'price');
                    currentUrl.searchParams.set('order', 'asc');
                } else if (value === 'price_desc') {
                    currentUrl.searchParams.set('sort', 'price');
                    currentUrl.searchParams.set('order', 'desc');
                } else if (value === 'name_asc') {
                    currentUrl.searchParams.set('sort', 'name');
                    currentUrl.searchParams.set('order', 'asc');
                } else {
                    currentUrl.searchParams.set('sort', 'created_at');
                    currentUrl.searchParams.set('order', 'desc');
                }
                currentUrl.searchParams.delete('page');
                window.location.href = currentUrl.toString();
            }
        });
    });

    // Accounting bounding evaluation limits range parameters execution
    const applyPriceBtn = document.getElementById('applyPriceBtn');
    if (applyPriceBtn) {
        applyPriceBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const min = document.getElementById('min_price').value;
            const max = document.getElementById('max_price').value;

            if (min) currentUrl.searchParams.set('min_price', min);
            else currentUrl.searchParams.delete('min_price');

            if (max) currentUrl.searchParams.set('max_price', max);
            else currentUrl.searchParams.delete('max_price');

            currentUrl.searchParams.delete('page');
            window.location.href = currentUrl.toString();
        });
    }
});

// Single target clear configuration blueprint
function clearFilter(parameter) {
    const url = new URL(window.location.href);
    url.searchParams.delete(parameter);
    url.searchParams.delete('page');
    window.location.href = url.toString();
}
</script>
@endsection