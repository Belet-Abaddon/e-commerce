@if($products->count() > 0)
    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
        @foreach($products as $product)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group flex flex-col justify-between">
                <a href="{{ route('user.products.show', $product->id) }}" class="block bg-gray-50 border-b">
                    @if($product->images && $product->images->count() > 0)
                        <img src="{{ Storage::url($product->images->first()->image_path) }}" 
                             class="w-full h-48 object-cover group-hover:scale-102 transition duration-300">
                    @else
                        <div class="w-full h-48 flex items-center justify-center">
                            <i class="fas fa-couch text-gray-300 text-4xl"></i>
                        </div>
                    @endif
                </a>
                <div class="p-4 flex-1 flex flex-col justify-between space-y-2">
                    <div>
                        <p class="text-[10px] font-extrabold uppercase tracking-wider text-gray-400 mb-0.5">
                            {{ $product->brand->name ?? 'Generic Brand' }}
                        </p>
                        <h3 class="font-bold text-gray-800 text-sm line-clamp-1 group-hover:text-blue-600 transition">
                            {{ $product->name }}
                        </h3>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                        <p class="text-base font-black text-blue-600">${{ number_format($product->price, 2) }}</p>
                        <a href="{{ route('user.products.show', $product->id) }}" 
                           class="bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-700 shadow-sm transition">
                            Order Now
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- System Pagination Wrapper Control Blocks Links execution link trace -->
    <div class="mt-8 border-t pt-4">
        {{ $products->links() }}
    </div>
@else
    <div class="text-center py-16 bg-white rounded-xl border border-gray-100 shadow-sm">
        <i class="fas fa-box-open text-5xl text-gray-200 mb-3 block"></i>
        <p class="text-gray-600 font-bold">No products found</p>
        <p class="text-xs text-gray-400 mt-1">Try resetting selected search queries or adjusting pricing bounds filters.</p>
    </div>
@endif