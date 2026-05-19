<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Feedback;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the user dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user statistics
        $totalOrders = Order::where('user_id', $user->id)->count();
        
        $totalSpent = Order::where('user_id', $user->id)
            ->join('product_orders', 'orders.id', '=', 'product_orders.order_id')
            ->join('products', 'product_orders.product_id', '=', 'products.id')
            ->sum(DB::raw('orders.qty * products.price'));
        
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['products', 'deliveries'])
            ->latest('order_date')
            ->take(5)
            ->get()
            ->map(function($order) {
                $order->total_amount = $order->products->sum(function($product) use ($order) {
                    return $product->price * $order->qty;
                });
                $order->delivery_status = $order->deliveries->first()->delivery_status ?? 'Pending';
                return $order;
            });
        
        $recentFeedbacks = Feedback::where('user_id', $user->id)
            ->latest()
            ->take(3)
            ->get();
        
        $pendingOrders = Order::where('user_id', $user->id)
            ->whereHas('deliveries', function($q) {
                $q->where('delivery_status', '!=', 'Delivered');
            })
            ->count();
        
        return view('dashboard', compact(
            'user', 
            'totalOrders', 
            'totalSpent', 
            'recentOrders', 
            'recentFeedbacks',
            'pendingOrders'
        ));
    }
}