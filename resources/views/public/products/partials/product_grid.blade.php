@if($products->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($products as $product)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group flex flex-col justify-between relative">
            <!-- Promotion Badge -->
            @if($product->has_promotion)
                <div class="absolute top-2 left-2 z-10">
                    <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-sm">
                        -{{ number_format($product->discount_percentage, 0) }}% OFF
                    </span>
                </div>
            @endif
            
            <a href="{{ route('public.products.show', $product->id) }}" class="block bg-gray-50 border-b">
                @if($product->images && $product->images->first())
                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                         class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                @else
                    <div class="w-full h-48 flex items-center justify-center">
                        <i class="fas fa-couch text-gray-300 text-4xl"></i>
                    </div>
                @endif
            </a>
            <div class="p-4">
                <p class="text-xs text-gray-500">{{ $product->brand->name ?? 'HomeNest' }}</p>
                <h3 class="font-bold text-gray-800 text-sm mt-1 line-clamp-1">{{ $product->name }}</h3>
                <div class="mt-2 pt-2 border-t border-gray-100">
                    @if($product->has_promotion)
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-400 line-through">${{ number_format($product->original_price, 2) }}</p>
                                <p class="text-lg font-bold text-red-600">${{ number_format($product->promotion_price, 2) }}</p>
                            </div>
                            <a href="{{ route('public.products.show', $product->id) }}" 
                               class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-700 transition">
                                View Details
                            </a>
                        </div>
                    @else
                        <div class="flex items-center justify-between">
                            <p class="text-lg font-bold text-blue-600">${{ number_format($product->price, 2) }}</p>
                            <a href="{{ route('public.products.show', $product->id) }}" 
                               class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-700 transition">
                                View Details
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $products->withQueryString()->links() }}
    </div>
@else
    <div class="text-center py-16 bg-white rounded-xl shadow-sm">
        <i class="fas fa-box-open text-5xl text-gray-300 mb-3"></i>
        <p class="text-gray-500 font-medium">No products found</p>
        <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filter criteria</p>
    </div>
@endif