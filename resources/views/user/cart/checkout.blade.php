@extends('layouts.app')

@section('title', 'Review Order Cart - HomeNest')
@section('header', 'Place Order')

@section('content')
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900">Review & Place Cart Order</h2>
                </div>

                @if(count($cart) > 0)
                    <form method="POST" action="{{ route('user.cart.process.checkout') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="p-6 space-y-6">
                            <!-- Product Items Summary -->
                            <div class="bg-blue-50/30 border border-blue-100 rounded-xl overflow-hidden shadow-sm">
                                <div class="px-4 py-3 bg-blue-50/70 border-b border-blue-100 flex justify-between items-center">
                                    <span class="text-sm font-bold text-blue-900"><i class="fas fa-box-open mr-2"></i>Items in
                                        Your Cart Bundle</span>
                                    <span
                                        class="bg-blue-200 text-blue-800 text-xs font-bold px-2.5 py-0.5 rounded-full">{{ count($cart) }}
                                        Distinct Models</span>
                                </div>
                                <div class="divide-y divide-gray-100 max-h-80 overflow-y-auto bg-white">
                                    @foreach($cart as $id => $item)
                                        <div class="p-4 flex items-center justify-between gap-4">
                                            <div class="flex items-center space-x-4 min-w-0">
                                                <div
                                                    class="w-12 h-12 bg-gray-50 border rounded-lg overflow-hidden flex-shrink-0 flex items-center justify-center">
                                                    @if($item['image'])
                                                        <img src="{{ asset('storage/' . $item['image']) }}"
                                                            class="w-full h-full object-cover">
                                                    @else
                                                        <i class="fas fa-couch text-gray-300 text-lg"></i>
                                                    @endif
                                                </div>
                                                <div class="min-w-0">
                                                    <h4 class="font-bold text-gray-900 text-sm truncate">{{ $item['name'] }}</h4>
                                                    @if(isset($item['has_promotion']) && $item['has_promotion'])
                                                        <div class="flex items-center gap-2">
                                                            <p class="text-xs text-gray-400 line-through">
                                                                ${{ number_format($item['original_price'], 2) }}</p>
                                                            <p class="text-xs font-bold text-red-600">
                                                                ${{ number_format($item['promotion_price'], 2) }}</p>
                                                            <span
                                                                class="text-[10px] bg-red-500 text-white px-1 py-0.5 rounded-full">-{{ number_format($item['discount_percentage'], 0) }}%</span>
                                                        </div>
                                                    @else
                                                        <p class="text-xs text-gray-400">Unit base price:
                                                            ${{ number_format($item['price'], 2) }}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="flex items-center space-x-6 flex-shrink-0">
                                                <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded">Qty:
                                                    {{ $item['qty'] }}</span>
                                                @php
                                                    $itemPrice = isset($item['final_price']) ? $item['final_price'] : (isset($item['promotion_price']) && $item['has_promotion'] ? $item['promotion_price'] : $item['price']);
                                                @endphp
                                                <span
                                                    class="text-sm font-black text-gray-900">${{ number_format($itemPrice * $item['qty'], 2) }}</span>
                                                <a href="{{ route('user.cart.remove', $id) }}"
                                                    class="text-red-500 hover:text-red-700 text-sm transition"
                                                    title="Remove Line Item">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Total Amount -->
                            <div class="bg-gray-50 p-4 border rounded-xl shadow-inner">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-gray-700 text-sm">Combined Bill Subtotal:</span>
                                    <span class="text-2xl font-black text-blue-600">${{ number_format($totalAmount, 2) }}</span>
                                </div>
                            </div>

                            <!-- Delivery Address -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Destination
                                    Shipping Address *</label>
                                <textarea name="order_address" rows="3"
                                    class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition @error('order_address') border-red-500 @enderror"
                                    placeholder="Provide complete shipping destination parameters..."
                                    required>{{ old('order_address', $user->address ?? '') }}</textarea>
                                @error('order_address')
                                    <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Delivery Mode -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Shipping
                                    Scope Category *</label>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <label
                                        class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition delivery-option bg-blue-50/40 border-blue-500">
                                        <input type="radio" name="delivery_type" value="local"
                                            class="mr-3 text-blue-600 focus:ring-blue-500 delivery-radio" checked>
                                        <div>
                                            <p class="font-bold text-sm text-gray-900">Local Delivery Logistics</p>
                                            <p class="text-xs text-gray-400">Within city or metropolitan boundaries</p>
                                        </div>
                                    </label>
                                    <label
                                        class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition delivery-option border-gray-200">
                                        <input type="radio" name="delivery_type" value="global"
                                            class="mr-3 text-blue-600 focus:ring-blue-500 delivery-radio">
                                        <div>
                                            <p class="font-bold text-sm text-gray-900">Global Carrier Cargo</p>
                                            <p class="text-xs text-gray-400">Cross-border international handling</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Delivery Courier -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Assigned
                                    Delivery Courier Line *</label>

                                <div id="localCouriers" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <label
                                        class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                        <input type="radio" name="delivery_name" value="Royal Express"
                                            class="mr-3 text-blue-600" checked>
                                        <div>
                                            <p class="font-bold text-sm text-gray-900">Royal Express Lines</p>
                                            <p class="text-xs text-gray-400">Premium door-to-door courier service</p>
                                        </div>
                                    </label>
                                    <label
                                        class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                        <input type="radio" name="delivery_name" value="Bee" class="mr-3 text-blue-600">
                                        <div>
                                            <p class="font-bold text-sm text-gray-900">Bee Delivery Logistics</p>
                                            <p class="text-xs text-gray-400">Eco-friendly prompt routing operations</p>
                                        </div>
                                    </label>
                                </div>

                                <div id="globalCouriers" class="grid grid-cols-1 md:grid-cols-2 gap-3 hidden">
                                    <label
                                        class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                        <input type="radio" name="delivery_name" value="FedEx" class="mr-3 text-blue-600">
                                        <div>
                                            <p class="font-bold text-sm text-gray-900">FedEx Air Priority</p>
                                            <p class="text-xs text-gray-400">Global tracking dispatch networks</p>
                                        </div>
                                    </label>
                                    <label
                                        class="flex items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition">
                                        <input type="radio" name="delivery_name" value="DHL" class="mr-3 text-blue-600">
                                        <div>
                                            <p class="font-bold text-sm text-gray-900">DHL Express Worldwide</p>
                                            <p class="text-xs text-gray-400">Fast tracking international cargo lines</p>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Consignee Name -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Consignee
                                    Full Name *</label>
                                <input type="text" value="{{ $user->name ?? '' }}" disabled
                                    class="w-full text-sm px-3 py-2 border border-gray-200 bg-gray-50 rounded-lg text-gray-500 font-medium">
                                <input type="hidden" name="delivery_name" value="{{ $user->name ?? '' }}">
                            </div>

                            <!-- Payment Methods -->
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Financial
                                    Payment Infrastructure *</label>
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <label
                                        class="flex flex-col items-start p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition payment-option bg-blue-50/40 border-blue-500">
                                        <input type="radio" name="payment_type" value="cash_on_delivery"
                                            class="mb-2 text-blue-600 payment-radio" checked data-needs-screenshot="false">
                                        <span class="font-bold text-sm text-gray-900">COD Method</span>
                                        <span class="text-[10px] text-gray-400">Pay cash upon delivery arrival</span>
                                    </label>
                                    <label
                                        class="flex flex-col items-start p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition payment-option border-gray-200">
                                        <input type="radio" name="payment_type" value="bank_transfer"
                                            class="mb-2 text-blue-600 payment-radio" data-needs-screenshot="true">
                                        <span class="font-bold text-sm text-gray-900">Bank Transfer</span>
                                        <span class="text-[10px] text-gray-400">Direct wire transfer processing</span>
                                    </label>
                                    <label
                                        class="flex flex-col items-start p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition payment-option border-gray-200">
                                        <input type="radio" name="payment_type" value="e_wallet"
                                            class="mb-2 text-blue-600 payment-radio" data-needs-screenshot="true">
                                        <span class="font-bold text-sm text-gray-900">E-Wallet</span>
                                        <span class="text-[10px] text-gray-400">Mobile electronic wallet apps</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Payment Reference -->
                            <div id="paymentProviderSection" class="hidden">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Payment
                                    Reference / Holder Name *</label>
                                <input type="text" name="payment_name" id="payment_name_input" value="Cash on Delivery Account"
                                    class="w-full text-sm px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500">
                            </div>

                            <!-- Screenshot Upload -->
                            <div id="screenshotSection" class="hidden">
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-2">Upload
                                    Remittance Receipt Screenshot *</label>
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-500 transition cursor-pointer bg-gray-50/50">
                                    <div class="space-y-2 text-center">
                                        <i class="fas fa-cloud-upload-alt text-3xl text-gray-400"></i>
                                        <div class="flex text-sm text-gray-600 justify-center">
                                            <label
                                                class="relative cursor-pointer font-bold text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                                <span>Select receipt file</span>
                                                <input type="file" id="screenshot" name="screenshot" class="sr-only"
                                                    accept="image/*" onchange="previewCartScreenshot(this)">
                                            </label>
                                        </div>
                                        <p class="text-[10px] text-gray-400 font-medium">JPEG, PNG file formats up to 2MB
                                            allowed</p>
                                    </div>
                                </div>
                                <div id="screenshotPreview" class="mt-3 hidden flex justify-center">
                                    <img id="previewImg"
                                        class="w-40 h-40 object-cover rounded-xl border p-1 bg-white shadow-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                            <a href="{{ route('user.products.index') }}"
                                class="px-5 py-2 border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                                Continue Browsing
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition shadow-sm flex items-center gap-2">
                                <i class="fas fa-lock text-xs"></i> Place Combined Order
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-16 p-6">
                        <i class="fas fa-shopping-basket text-6xl text-gray-200 mb-4 block"></i>
                        <p class="text-gray-600 font-bold text-lg">Your order cart is empty</p>
                        <p class="text-sm text-gray-400 mt-1 mb-6">Add products from our collection to initialize a batch
                            checkout review.</p>
                        <a href="{{ route('user.products.index') }}"
                            class="inline-flex bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm py-2 px-6 rounded-lg transition shadow-sm">
                            ← Browse Products Catalog
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function highlightSelectedOption(radioElement, classNameSelector) {
            document.querySelectorAll('.' + classNameSelector).forEach(wrapper => {
                wrapper.classList.remove('bg-blue-50/40', 'border-blue-500');
                wrapper.classList.add('border-gray-200');
            });
            const targetedWrapper = radioElement.closest('.' + classNameSelector);
            targetedWrapper.classList.remove('border-gray-200');
            targetedWrapper.classList.add('bg-blue-50/40', 'border-blue-500');
        }

        document.querySelectorAll('.delivery-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                highlightSelectedOption(this, 'delivery-option');

                const localBox = document.getElementById('localCouriers');
                const globalBox = document.getElementById('globalCouriers');

                if (this.value === 'local') {
                    localBox.classList.remove('hidden');
                    globalBox.classList.add('hidden');
                    document.querySelector('#localCouriers input[type="radio"]').checked = true;
                } else {
                    localBox.classList.add('hidden');
                    globalBox.classList.remove('hidden');
                    document.querySelector('#globalCouriers input[type="radio"]').checked = true;
                }
            });
        });

        document.querySelectorAll('.payment-radio').forEach(radio => {
            radio.addEventListener('change', function () {
                highlightSelectedOption(this, 'payment-option');

                const needsScreenshot = (this.dataset.needsScreenshot === 'true');
                const providerSection = document.getElementById('paymentProviderSection');
                const screenshotSection = document.getElementById('screenshotSection');
                const inputField = document.getElementById('payment_name_input');
                const fileField = document.getElementById('screenshot');

                if (needsScreenshot) {
                    providerSection.classList.remove('hidden');
                    screenshotSection.classList.remove('hidden');
                    inputField.value = "";
                    inputField.placeholder = "Enter account transaction reference details...";
                    fileField.required = true;
                } else {
                    providerSection.classList.add('hidden');
                    screenshotSection.classList.add('hidden');
                    inputField.value = "Cash on Delivery Account";
                    fileField.required = false;
                }
            });
        });

        function previewCartScreenshot(input) {
            const previewBlock = document.getElementById('screenshotPreview');
            const imgElement = document.getElementById('previewImg');

            if (input.files && input.files[0]) {
                const fileReader = new FileReader();
                fileReader.onload = function (e) {
                    imgElement.src = e.target.result;
                    previewBlock.classList.remove('hidden');
                };
                fileReader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection