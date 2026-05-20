<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use App\Models\Promotion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Get active promotion for a product at a specific date.
     *
     * @param int $productId
     * @param string $orderDate
     * @return mixed
     */
    private function getPromotionAtDate($productId, $orderDate)
    {
        $promotion = DB::table('promotion_products')
            ->join('promotions', 'promotion_products.promotion_id', '=', 'promotions.id')
            ->where('promotion_products.product_id', $productId)
            ->where('promotions.start_date', '<=', $orderDate)
            ->where('promotions.end_date', '>=', $orderDate)
            ->select('promotion_products.percentage')
            ->first();
        
        return $promotion;
    }

    /**
     * Calculate discounted price at a specific date.
     *
     * @param float $price
     * @param int $productId
     * @param string $orderDate
     * @return float
     */
    private function getDiscountedPriceAtDate($price, $productId, $orderDate)
    {
        $promotion = $this->getPromotionAtDate($productId, $orderDate);
        
        if ($promotion && isset($promotion->percentage)) {
            $discount = $promotion->percentage;
            $discountedPrice = $price - ($price * $discount / 100);
            return round($discountedPrice, 2);
        }
        
        return $price;
    }

    /**
     * Display user's order history.
     */
    public function index(Request $request): View
    {
        $query = Order::where('user_id', Auth::id())
            ->with(['products', 'deliveries']);
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->whereHas('deliveries', function($q) use ($request) {
                $q->where('delivery_status', $request->status);
            });
        }
        
        $orders = $query->latest('order_date')->paginate(10);
        
        // Calculate total amount for each order with promotion prices
        foreach ($orders as $order) {
            $totalAmount = 0;
            foreach ($order->products as $product) {
                // Get promotion price at the time of order
                $priceAtOrder = $this->getDiscountedPriceAtDate($product->price, $product->id, $order->order_date);
                $totalAmount += $priceAtOrder * $order->qty;
                
                // Store calculated price for display
                $product->price_at_order = $priceAtOrder;
                $product->has_promotion_at_order = ($priceAtOrder < $product->price);
            }
            $order->total_amount = $totalAmount;
            $order->delivery_status = $order->deliveries->first()->delivery_status ?? 'pending';
        }
        
        return view('user.orders.index', compact('orders'));
    }
    
    /**
     * Display order details.
     */
    public function show(int $id): View
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['products', 'products.images', 'products.productType', 'products.brand', 'deliveries', 'payments'])
            ->findOrFail($id);
        
        // Calculate total amount with promotion prices at time of order
        $totalAmount = 0;
        foreach ($order->products as $product) {
            // Get promotion price at the time of order
            $priceAtOrder = $this->getDiscountedPriceAtDate($product->price, $product->id, $order->order_date);
            $totalAmount += $priceAtOrder * $order->qty;
            
            // Store calculated price for display
            $product->price_at_order = $priceAtOrder;
            $product->has_promotion_at_order = ($priceAtOrder < $product->price);
            $product->discount_percentage = $product->has_promotion_at_order 
                ? round((($product->price - $priceAtOrder) / $product->price) * 100, 0)
                : 0;
        }
        $order->total_amount = $totalAmount;
        $order->delivery_status = $order->deliveries->first()->delivery_status ?? 'pending';
        
        // Check if order can be cancelled
        $canCancel = in_array($order->delivery_status, ['pending']);
        
        return view('user.orders.show', compact('order', 'canCancel'));
    }
    
    /**
     * Cancel order.
     */
    public function cancel(int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            
            $order = Order::where('user_id', Auth::id())->findOrFail($id);
            
            // Get or create delivery record
            $delivery = Delivery::firstOrCreate(
                ['order_id' => $order->id],
                ['delivery_status' => 'pending']
            );
            
            // Check if order can be cancelled
            if ($delivery->delivery_status !== 'pending') {
                DB::rollBack();
                return redirect()->back()->with('error', 'This order cannot be cancelled. Current status: ' . ucfirst($delivery->delivery_status));
            }
            
            // Update delivery status to cancelled
            $delivery->delivery_status = 'cancelled';
            $delivery->save();
            
            DB::commit();
            
            return redirect()->route('user.orders.index')
                ->with('success', 'Order #' . str_pad($order->id, 6, '0', STR_PAD_LEFT) . ' has been cancelled successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to cancel order. Please try again. Error: ' . $e->getMessage());
        }
    }
}