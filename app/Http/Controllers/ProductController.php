<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductType;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
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
        
        // Check if product has promotions relationship
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
     * Display product catalog with synchronized query filters.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['brand', 'productType', 'images', 'promotions'])
            ->where('status', 'active');

        // Search by product text string value
        if ($request->has('search') && !empty($request->input('search'))) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        // Filter by brand selection parameter context
        if ($request->has('brand_id') && !empty($request->input('brand_id'))) {
            $query->where('brand_id', $request->input('brand_id'));
        }

        // Filter by structural category data type identifier
        if ($request->has('product_type_id') && !empty($request->input('product_type_id'))) {
            $query->where('product_type_id', $request->input('product_type_id'));
        }

        // Filter by calculated currency bounding ranges
        if ($request->has('min_price') && !empty($request->input('min_price'))) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price') && !empty($request->input('max_price'))) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Sort configuration evaluations mapping rules
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');

        if ($sort == 'price') {
            $query->orderBy('price', $order);
        } elseif ($sort == 'name') {
            $query->orderBy('name', $order);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();

        // Calculate promotion prices for each product
        foreach ($products as $product) {
            $promotion = $this->getActivePromotion($product);
            $product->has_promotion = $promotion ? true : false;
            $product->discount_percentage = $promotion ? $promotion->pivot->percentage : 0;
            $product->original_price = $product->price;
            $product->promotion_price = $this->getDiscountedPrice($product, $promotion);
        }

        // Count product assets mapped securely per brand item block references
        $brands = Brand::withCount(['products' => function($q) {
            $q->where('status', 'active');
        }])->get();
        
        $productTypes = ProductType::all();

        // Highly compatible subquery calculating most ordered items by reading layout qty from orders
        $mostOrdered = Product::with(['brand', 'images', 'promotions'])
            ->where('status', 'active')
            ->withCount(['orders as total_units_sold' => function ($q) {
                $q->select(DB::raw('SUM(orders.qty)'));
            }])
            ->orderByDesc('total_units_sold')
            ->limit(4)
            ->get();

        // Calculate promotion prices for most ordered products
        foreach ($mostOrdered as $product) {
            $promotion = $this->getActivePromotion($product);
            $product->has_promotion = $promotion ? true : false;
            $product->discount_percentage = $promotion ? $promotion->pivot->percentage : 0;
            $product->original_price = $product->price;
            $product->promotion_price = $this->getDiscountedPrice($product, $promotion);
        }

        // Discover absolute pricing limits for view configuration parameters
        $minPrice = Product::where('status', 'active')->min('price') ?? 0;
        $maxPrice = Product::where('status', 'active')->max('price') ?? 5000;

        return view('user.products.index', compact(
            'products',
            'brands',
            'productTypes',
            'mostOrdered',
            'minPrice',
            'maxPrice'
        ));
    }

    /**
     * Display unique product data resource profile view layout sheet.
     */
    public function show(int $id): View
    {
        $product = Product::with(['brand', 'productType', 'images', 'promotions'])->findOrFail($id);
        
        // Calculate promotion price
        $promotion = $this->getActivePromotion($product);
        $product->has_promotion = $promotion ? true : false;
        $product->discount_percentage = $promotion ? $promotion->pivot->percentage : 0;
        $product->original_price = $product->price;
        $product->promotion_price = $this->getDiscountedPrice($product, $promotion);

        $relatedProducts = Product::with(['brand', 'images', 'promotions'])
            ->where('status', 'active')
            ->where('id', '!=', $product->id)
            ->where(function ($query) use ($product) {
                $query->where('brand_id', $product->brand_id)
                      ->orWhere('product_type_id', $product->product_type_id);
            })
            ->limit(4)
            ->get();

        // Calculate promotion prices for related products
        foreach ($relatedProducts as $related) {
            $relatedPromotion = $this->getActivePromotion($related);
            $related->has_promotion = $relatedPromotion ? true : false;
            $related->discount_percentage = $relatedPromotion ? $relatedPromotion->pivot->percentage : 0;
            $related->original_price = $related->price;
            $related->promotion_price = $this->getDiscountedPrice($related, $relatedPromotion);
        }

        return view('user.products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Display order checkout verification processing step layout context.
     */
    public function orderForm(int $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please authenticate credentials access to complete your order.');
        }

        $product = Product::with(['brand', 'productType', 'images'])->findOrFail($id);
        return view('user.products.order', compact('product'));
    }

    /**
     * Place order single pipeline checkout method processing logic trail.
     */
    public function placeOrder(Request $request, int $id): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please authorize session identity to process checkout.');
        }

        $request->validate([
            'qty'           => 'required|integer|min:1',
            'order_address' => 'required|string|min:5',
            'delivery_type' => 'required|in:local,global',
            'delivery_name' => 'required|string',
            'payment_type'  => 'required|in:cash_on_delivery,bank_transfer,e_wallet',
            'payment_name'  => 'required|string',
            'screenshot'    => 'required_if:payment_type,bank_transfer,e_wallet|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $product = Product::findOrFail($id);

        // Execute queries inside structured atomic database transaction blocks
        DB::transaction(function () use ($request, $product) {
            
            // NO MIGRATION COMPATIBLE MODE: Saves qty directly here onto the orders schema column fields pool
            $order = Order::create([
                'order_date'    => now(),
                'order_address' => $request->order_address,
                'delivery_type' => $request->delivery_type,
                'delivery_name' => $request->delivery_name,
                'user_id'       => Auth::id(),
                'qty'           => intval($request->qty),
            ]);

            // NO MIGRATION COMPATIBLE MODE: Clean standard relationship attach mapping definition parameter tracking arrays
            // This safely bypasses calling "price" or "qty" on product_orders pivot columns since they are absent
            $order->products()->attach($product->id);

            // Instantiate shipment lifecycle logging entry
            Delivery::create([
                'delivery_status' => 'pending',
                'order_id'        => $order->id,
            ]);

            // Capture file screenshot attachment variables traces
            $screenshotPath = null;
            if ($request->hasFile('screenshot')) {
                $screenshotPath = $request->file('screenshot')->store('receipts', 'public');
            }

            // Generate accounting ledger balance map
            Payment::create([
                'payment_type' => $request->payment_type,
                'payment_name' => $request->payment_name ?? 'Cash on Delivery Account',
                'order_id'     => $order->id,
                'screenshot'   => $screenshotPath,
            ]);
        });

        return redirect()->route('dashboard')
            ->with('success', 'Order was processed successfully! Current shipping tracking milestone initialized to pending.');
    }

    /**
     * Filter products catalog via client processing actions (Fallback interface mapping).
     */
    public function filterByPrice(Request $request): string
    {
        $query = Product::with(['brand', 'productType', 'images'])
            ->where('status', 'active');

        if ($request->has('min_price') && !empty($request->input('min_price'))) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price') && !empty($request->input('max_price'))) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        $products = $query->paginate(12);

        return view('user.products.partials.product_grid', compact('products'))->render();
    }

    /**
     * Fetch products associated to matching identification tracking markers.
     */
    public function getByBrand(int $brandId): string
    {
        $products = Product::with(['brand', 'productType', 'images'])
            ->where('status', 'active')
            ->where('brand_id', $brandId)
            ->paginate(12);

        return view('user.products.partials.product_grid', compact('products'))->render();
    }
}