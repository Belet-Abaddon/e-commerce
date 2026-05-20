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

class CartController extends Controller
{
    /**
     * Display the current shopping cart items list and absolute sum value.
     */
    public function index(): View
    {
        // Extract array stack from session memory storage arrays
        $cart = session()->get('cart', []);
        $totalAmount = 0;

        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['qty'];
        }

        return view('user.cart.index', compact('cart', 'totalAmount'));
    }

    /**
     * Add a target selection product catalog row into active cart tracking list sessions.
     */
    public function add(Request $request, int $id): RedirectResponse
    {
        $product = Product::with('images')->findOrFail($id);
        $qty = intval($request->get('qty', 1));
        
        $cart = session()->get('cart', []);

        // Increment volume index if item target trace reference pre-exists inside list map
        if (isset($cart[$id])) {
            $cart[$id]['qty'] += $qty;
        } else {
            // Push structured metadata properties schema mapping definitions down to stack trace
            $cart[$id] = [
                "name"  => $product->name,
                "qty"   => $qty,
                "price" => $product->price,
                "image" => $product->images->first()?->image_path ?? null
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('user.products.index')
            ->with('success', "{$product->name} was packed into your multi-product shopping cart bundle!");
    }

    /**
     * Drop individual item lines out of active tracking checkout pool arrays.
     */
    public function remove(int $id): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Selected item line removed from cart bundle configuration.');
    }

    /**
     * Process combined checkout entries safely within a database transaction block wrapper.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('user.products.index')->with('error', 'Your shopping shopping basket allocation registry is empty.');
        }

        $request->validate([
            'order_address' => 'required|string|min:5',
            'delivery_name' => 'required|string|max:255',
            'delivery_type' => 'required|string',
            'payment_type'  => 'required|string',
            'payment_name'  => 'required|string',
            'screenshot'    => 'nullable|image|max:2048'
        ]);

        // Fixes the Intelephense P1006 type mismatch by fetching and verifying the true user object profile state first
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please authorize session access authentication credentials to complete checkout.');
        }

        // Secure operation execution trace to enforce atomic data safety
        DB::transaction(function() use ($request, $cart, $user) {
            
            // 1. Instantiate the singular parent transaction mapping context block header 
            $order = Order::create([
                'order_date'    => now(),
                'order_address' => $request->order_address,
                'delivery_type' => $request->delivery_type,
                'delivery_name' => $request->delivery_name,
                'user_id'       => $user->id, // Safely reference the typed parameter variable here
                'qty'           => array_sum(array_column($cart, 'qty')) // Roll-up quantity mapping for legacy schema stability
            ]);

            // 2. Loop individual index objects array data definitions to attach pivots cleanly
            foreach ($cart as $productId => $details) {
                $order->products()->attach($productId, [
                    'qty'   => $details['qty'],
                    'price' => $details['price'] // Historical checkout snapshot tracking value rule
                ]);
            }

            // 3. Trigger shipping milestone system log reference initial state directly to "pending"
            Delivery::create([
                'delivery_status' => 'pending',
                'order_id'        => $order->id
            ]);

            // 4. Save file receipt attachment verification parameter tracks to safe directory structures
            $screenshotPath = null;
            if ($request->hasFile('screenshot')) {
                $screenshotPath = $request->file('screenshot')->store('receipts', 'public');
            }

            // 5. Generate matching financial account ledger item parameters
            Payment::create([
                'payment_type' => $request->payment_type,
                'payment_name' => $request->payment_name,
                'order_id'     => $order->id,
                'screenshot'   => $screenshotPath
            ]);
        });

        // Wipe data structure out of session memory pools upon transactional clearance verification
        session()->forget('cart');

        return redirect()->route('dashboard')
            ->with('success', 'Your consolidated batch order went through! Delivery tracking status initialized to pending.');
    }
}