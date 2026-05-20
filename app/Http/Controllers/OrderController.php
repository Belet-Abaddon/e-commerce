<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
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
        
        // Calculate total amount for each order
        foreach ($orders as $order) {
            $order->total_amount = $order->products->sum(function($product) use ($order) {
                return $product->price * $order->qty;
            });
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
        
        $order->total_amount = $order->products->sum(function($product) use ($order) {
            return $product->price * $order->qty;
        });
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
