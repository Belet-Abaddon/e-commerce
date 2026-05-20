@extends('layouts.app')

@section('title', 'Shopping Cart - HomeNest')
@section('header', 'My Cart')

@section('content')
<div class="py-6">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded shadow-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif

        @if(count($cart) > 0)
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Shopping Cart</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ count($cart) }} item(s) in your cart</p>
                </div>

                <div class="divide-y divide-gray-200">
                    @foreach($cart as $id => $item)
                    <div class="p-6 flex flex-col sm:flex-row items-center gap-4 sm:gap-6">
                        <div class="w-24 h-24 bg-gray-50 rounded-lg border overflow-hidden flex-shrink-0 flex items-center justify-center">
                            @if($item['image'])
                                <img src="{{ asset('storage/' . $item['image']) }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-couch text-gray-300 text-3xl"></i>
                            @endif
                        </div>

                        <div class="flex-1">
                            <h3 class="font-bold text-gray-900 text-lg">{{ $item['name'] }}</h3>
                            @if(isset($item['has_promotion']) && $item['has_promotion'])
                                <div class="flex items-center gap-2 mt-1">
                                    <p class="text-sm text-gray-400 line-through">${{ number_format($item['original_price'], 2) }}</p>
                                    <p class="text-sm font-bold text-red-600">${{ number_format($item['promotion_price'], 2) }}</p>
                                    <span class="text-xs bg-red-500 text-white px-1.5 py-0.5 rounded-full">-{{ number_format($item['discount_percentage'], 0) }}%</span>
                                </div>
                            @else
                                <p class="text-sm text-gray-500">Unit Price: ${{ number_format($item['price'], 2) }}</p>
                            @endif
                        </div>

                        <div class="flex items-center gap-6">
                            <div class="text-center">
                                <p class="text-xs text-gray-500 mb-1">Quantity</p>
                                <div class="flex items-center border border-gray-300 rounded-lg overflow-hidden">
                                    <a href="{{ route('user.cart.update', $id) }}?qty={{ $item['qty'] - 1 }}" 
                                       class="px-3 py-1 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold {{ $item['qty'] <= 1 ? 'opacity-50 pointer-events-none' : '' }}">
                                        -
                                    </a>
                                    <span class="w-12 text-center text-sm font-bold text-gray-900">{{ $item['qty'] }}</span>
                                    <a href="{{ route('user.cart.update', $id) }}?qty={{ $item['qty'] + 1 }}" 
                                       class="px-3 py-1 bg-gray-50 hover:bg-gray-100 text-gray-600 font-bold">
                                        +
                                    </a>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500 mb-1">Subtotal</p>
                                <p class="text-xl font-bold text-blue-600">${{ number_format($item['price'] * $item['qty'], 2) }}</p>
                            </div>
                            <a href="{{ route('user.cart.remove', $id) }}" 
                               class="text-red-500 hover:text-red-700 transition"
                               onclick="return confirm('Remove this item from cart?')">
                                <i class="fas fa-trash-alt text-lg"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex gap-3">
                            <a href="{{ route('user.products.index') }}" class="px-5 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                                <i class="fas fa-arrow-left mr-2"></i> Continue Shopping
                            </a>
                            <a href="{{ route('user.cart.clear') }}" 
                               class="px-5 py-2 border border-red-300 rounded-lg text-sm font-semibold text-red-600 hover:bg-red-50 transition"
                               onclick="return confirm('Clear entire cart?')">
                                <i class="fas fa-trash-alt mr-2"></i> Clear Cart
                            </a>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total Amount</p>
                            <p class="text-3xl font-black text-blue-600">${{ number_format($totalAmount, 2) }}</p>
                            <a href="{{ route('user.cart.checkout') }}" class="mt-3 inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition shadow-md">
                                <i class="fas fa-credit-card mr-2"></i> Proceed to Checkout
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-white rounded-xl shadow-sm p-12 text-center border border-gray-200">
                <i class="fas fa-shopping-cart text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Your cart is empty</h3>
                <p class="text-gray-500 mb-6">Looks like you haven't added any items to your cart yet.</p>
                <a href="{{ route('user.products.index') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition shadow-md">
                    <i class="fas fa-store mr-2"></i> Start Shopping
                </a>
            </div>
        @endif
    </div>
</div>
@endsection