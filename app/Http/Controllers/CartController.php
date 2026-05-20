<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Mail\OrderConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Get active promotion for a product.
     *
     * @param Product|object $product
     * @return mixed
     */
    private function getActivePromotion($product)
    {
        $today = date('Y-m-d');

        if (method_exists($product, 'promotions')) {
            $promotion = $product->promotions()
                ->where('start_date', '<=', $today)
                ->where('end_date', '>=', $today)
                ->first();

            return $promotion;
        }

        return null;
    }

    /**
     * Calculate discounted price.
     *
     * @param Product|object $product
     * @param mixed $promotion
     * @return float
     */
    private function getDiscountedPrice($product, $promotion = null)
    {
        if (!$promotion) {
            $promotion = $this->getActivePromotion($product);
        }

        if ($promotion && isset($promotion->pivot->percentage)) {
            $discount = $promotion->pivot->percentage;
            $discountedPrice = $product->price - ($product->price * $discount / 100);
            return round($discountedPrice, 2);
        }

        return (float) $product->price;
    }

    /**
     * Display the current shopping cart items list and absolute sum value.
     */
    public function index(Request $request): View
    {
        $cart = (array) $request->session()->get('cart', []);
        $totalAmount = 0;

        foreach ($cart as $item) {
            if (is_array($item)) {
                $totalAmount += $item['price'] * $item['qty'];
            }
        }

        return view('user.cart.index', compact('cart', 'totalAmount'));
    }

    /**
     * Add a target selection product catalog row into active cart tracking list sessions.
     */
    public function add(Request $request, int $id): RedirectResponse
    {
        /** @var \App\Models\Product $product */
        $product = Product::with('images', 'promotions')->findOrFail($id);
        $qty = intval($request->input('qty', 1));
        
        $promotion = $this->getActivePromotion($product);
        $hasPromotion = $promotion ? true : false;
        $discountPercentage = $promotion ? $promotion->pivot->percentage : 0;
        $originalPrice = $product->price;
        $promotionPrice = $this->getDiscountedPrice($product, $promotion);
        $finalPrice = $hasPromotion ? $promotionPrice : $originalPrice;
        
        $cart = (array) $request->session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            $cart[$id] = [
                "name"               => $product->name,
                "qty"                => $qty,
                "price"              => $finalPrice,
                "original_price"     => $originalPrice,
                "promotion_price"    => $promotionPrice,
                "has_promotion"      => $hasPromotion,
                "discount_percentage"=> $discountPercentage,
                "image"              => $product->images->first()?->image_path ?? null
            ];
        }

        $request->session()->put('cart', $cart);

        $message = $hasPromotion 
            ? "{$product->name} was added to your cart with {$discountPercentage}% discount!" 
            : "{$product->name} was added to your cart!";

        return redirect()->route('user.products.index')->with('success', $message);
    }

    /**
     * Update item quantity in cart.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $cart = (array) $request->session()->get('cart', []);
        $qty = intval($request->input('qty', 1));
        
        if (isset($cart[$id])) {
            if ($qty <= 0) {
                unset($cart[$id]);
            } else {
                $cart[$id]['qty'] = $qty;
            }
            $request->session()->put('cart', $cart);
        }
        
        return redirect()->route('user.cart.index')->with('success', 'Cart updated successfully!');
    }

    /**
     * Drop individual item lines out of active tracking checkout pool arrays.
     */
    public function remove(Request $request, int $id): RedirectResponse
    {
        $cart = (array) $request->session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            $request->session()->put('cart', $cart);
        }

        return redirect()->route('user.cart.index')->with('success', 'Item removed from cart!');
    }

    /**
     * Clear entire cart.
     */
    public function clear(Request $request): RedirectResponse
    {
        $request->session()->forget('cart');
        return redirect()->route('user.cart.index')->with('success', 'Cart cleared successfully!');
    }

    /**
     * Show checkout form.
     */
    public function checkoutForm(Request $request)
    {
        $cart = (array) $request->session()->get('cart', []);
        $totalAmount = 0;
        
        foreach ($cart as $item) {
            if (is_array($item)) {
                $itemPrice = isset($item['price']) ? $item['price'] : 0;
                $totalAmount += $itemPrice * $item['qty'];
            }
        }
        
        if (empty($cart)) {
            return redirect()->route('user.cart.index')->with('error', 'Your cart is empty.');
        }
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        return view('user.cart.checkout', compact('cart', 'totalAmount', 'user'));
    }

    /**
     * Process combined checkout entries safely within a database transaction block wrapper.
     */
    public function processCheckout(Request $request): RedirectResponse
    {
        $cart = (array) $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('user.products.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'order_address' => 'required|string|min:5',
            'delivery_type' => 'required|string',
            'delivery_name' => 'required|string',
            'payment_type'  => 'required|string',
            'payment_name'  => 'required|string',
            'screenshot'    => 'nullable|image|max:2048'
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to complete checkout.');
        }

        $order = null;

        DB::transaction(function() use ($request, $cart, $user, &$order) {
            
            /** @var \App\Models\Order $order */
            $order = Order::create([
                'order_date'    => now(),
                'order_address' => $request->order_address,
                'delivery_type' => $request->delivery_type,
                'delivery_name' => $request->delivery_name,
                'user_id'       => $user->id,
                'qty'           => array_sum(array_column($cart, 'qty'))
            ]);

            foreach ($cart as $productId => $details) {
                $order->products()->attach($productId);
            }

            Delivery::create([
                'delivery_status' => 'pending',
                'order_id'        => $order->id
            ]);

            $screenshotPath = null;
            if ($request->hasFile('screenshot')) {
                $screenshotPath = $request->file('screenshot')->store('receipts', 'public');
            }

            Payment::create([
                'payment_type' => $request->payment_type,
                'payment_name' => $request->payment_name,
                'order_id'     => $order->id,
                'screenshot'   => $screenshotPath
            ]);
        });

        // Calculate total amount for email
        $totalAmount = 0;
        foreach ($cart as $item) {
            if (is_array($item)) {
                $itemPrice = isset($item['price']) ? $item['price'] : 0;
                $totalAmount += $itemPrice * $item['qty'];
            }
        }

        // Send order confirmation email only if order exists
        if ($order) {
            try {
                Mail::to($user->email)->send(new OrderConfirmationMail($order, $cart, $totalAmount));
            } catch (\Exception $e) {
                Log::error('Failed to send order confirmation email: ' . $e->getMessage());
            }
        }

        $request->session()->forget('cart');

        return redirect()->route('dashboard')
            ->with('success', 'Your order has been placed successfully!' . ($order ? ' A confirmation email has been sent to ' . $user->email : ''));
    }
}