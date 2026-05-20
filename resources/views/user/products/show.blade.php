@extends('layouts.app')

@section('title', $product->name . ' - HomeNest')
@section('header', $product->name)

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if (session('success'))
            <div class="mb-6 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-500 text-lg"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-6 text-sm">
            <a href="{{ route('user.products.index') }}" class="text-gray-500 hover:text-blue-600 transition">Products</a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-900 font-medium">{{ $product->name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Media Box (Left Side) -->
            <div class="space-y-4">
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden p-2 shadow-sm">
                    @if($product->images && $product->images->first())
                        <img id="mainImage" src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                             class="w-full h-96 object-contain bg-gray-50 rounded-lg">
                    @else
                        <div class="w-full h-96 bg-gray-50 flex items-center justify-center rounded-lg">
                            <i class="fas fa-couch text-gray-300 text-6xl"></i>
                        </div>
                    @endif
                </div>
                
                @if($product->images && $product->images->count() > 1)
                <div class="grid grid-cols-4 gap-2">
                    @foreach($product->images as $image)
                    <div class="cursor-pointer border-2 border-transparent rounded-lg overflow-hidden hover:border-blue-500 transition p-0.5 bg-white shadow-sm" 
                         onclick="document.getElementById('mainImage').src = '{{ asset('storage/' . $image->image_path) }}'">
                        <img src="{{ asset('storage/' . $image->image_path) }}" class="w-full h-20 object-cover rounded-md">
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- Operational Summary Block (Right Side) -->
            <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
                <div class="space-y-6">
                    <div>
                        <span class="text-xs uppercase font-extrabold tracking-wider text-blue-600 bg-blue-50 px-2.5 py-1 rounded-md">
                            {{ $product->brand->name ?? 'HomeNest Collection' }}
                        </span>
                        <h1 class="text-2xl font-extrabold text-gray-900 mt-3">{{ $product->name }}</h1>
                        
                        <div class="flex items-center gap-3 mt-3">
                            @if(isset($product->has_promotion) && $product->has_promotion)
                                <div>
                                    <p class="text-sm text-gray-400 line-through">${{ number_format($product->original_price, 2) }}</p>
                                    <p class="text-3xl font-black text-red-600">${{ number_format($product->promotion_price, 2) }}</p>
                                    <span class="inline-block mt-1 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                        Save {{ number_format($product->discount_percentage, 0) }}%
                                    </span>
                                </div>
                            @else
                                <p class="text-3xl font-black text-blue-600">${{ number_format($product->price, 2) }}</p>
                            @endif
                            
                            @if($product->status == 'out_of_stock')
                                <span class="px-2.5 py-0.5 bg-red-100 text-red-700 font-bold text-xs rounded-full">Out of Stock</span>
                            @else
                                <span class="px-2.5 py-0.5 bg-green-100 text-green-700 font-bold text-xs rounded-full">🟢 In Stock</span>
                            @endif
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Item Specifications</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm bg-gray-50 p-3 rounded-lg border">
                            <div>
                                <span class="text-gray-500">Category:</span>
                                <span class="ml-1 font-semibold text-gray-800">{{ $product->productType->name ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Brand:</span>
                                <span class="ml-1 font-semibold text-gray-800">{{ $product->brand->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <h3 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-1">Product Description</h3>
                        <p class="text-sm text-gray-600 leading-relaxed">{{ $product->description ?: 'No detailed written description provided.' }}</p>
                    </div>
                </div>

                <!-- Add to Cart Section -->
                @if($product->status !== 'out_of_stock')
                    <div class="border-t border-gray-100 pt-6 mt-6">
                        <form method="POST" action="{{ route('user.cart.add', $product->id) }}" class="space-y-4">
                            @csrf
                            
                            <div class="flex items-center justify-between bg-blue-50/50 p-4 rounded-xl border border-blue-100/50">
                                <label class="text-sm font-bold text-gray-700">Select Quantity:</label>
                                <div class="flex items-center border border-gray-300 rounded-lg bg-white overflow-hidden w-32 shadow-sm">
                                    <button type="button" onclick="adjustQty(-1)" class="px-3 py-1 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold focus:outline-none">-</button>
                                    <input type="number" name="qty" id="purchase_qty" value="1" min="1" class="w-full text-center border-0 text-sm font-bold text-gray-900 focus:ring-0">
                                    <button type="button" onclick="adjustQty(1)" class="px-3 py-1 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold focus:outline-none">+</button>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-base py-3 px-6 rounded-xl shadow-md transition flex items-center justify-center gap-2">
                                <i class="fas fa-shopping-cart"></i> Add to Cart
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-red-50 border border-red-200 p-4 rounded-xl text-center text-red-700 font-medium text-sm mt-6">
                        This item is currently completely out of stock.
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Related Products Section -->
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <div class="mt-12">
            <h2 class="text-xl font-bold text-gray-900 mb-4">You May Also Like</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($relatedProducts as $related)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition relative">
                    @if(isset($related->has_promotion) && $related->has_promotion)
                        <div class="absolute top-2 left-2 z-10">
                            <span class="bg-red-500 text-white text-xs font-bold px-1.5 py-0.5 rounded-full">
                                -{{ number_format($related->discount_percentage, 0) }}%
                            </span>
                        </div>
                    @endif
                    <a href="{{ route('user.products.show', $related->id) }}" class="block bg-gray-50">
                        @if($related->images && $related->images->first())
                            <img src="{{ asset('storage/' . $related->images->first()->image_path) }}" class="w-full h-32 object-cover">
                        @else
                            <div class="w-full h-32 flex items-center justify-center">
                                <i class="fas fa-couch text-gray-300 text-2xl"></i>
                            </div>
                        @endif
                    </a>
                    <div class="p-3">
                        <h3 class="font-bold text-gray-800 text-xs line-clamp-1">{{ $related->name }}</h3>
                        @if(isset($related->has_promotion) && $related->has_promotion)
                            <p class="text-xs text-gray-400 line-through">${{ number_format($related->original_price, 2) }}</p>
                            <p class="text-sm font-black text-red-600">${{ number_format($related->promotion_price, 2) }}</p>
                        @else
                            <p class="text-sm font-black text-blue-600">${{ number_format($related->price, 2) }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    function adjustQty(modifier) {
        const input = document.getElementById('purchase_qty');
        let currentVal = parseInt(input.value) || 1;
        currentVal += modifier;
        if (currentVal < 1) currentVal = 1;
        input.value = currentVal;
    }
</script>
@endsection