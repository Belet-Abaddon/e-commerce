<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductType;
use App\Models\Brand;
use App\Models\Feedback;
use App\Models\Promotion;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Total counts
        $totalUsers = User::count();
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalFeedbacks = Feedback::count();
        $totalBrands = Brand::count();
        $totalProductTypes = ProductType::count();
        $activePromotions = Promotion::where('end_date', '>=', now())
                                     ->where('start_date', '<=', now())
                                     ->count();
        
        // Fixed: Total Revenue calculation using join instead of subquery
        $totalRevenueDetailed = DB::table('orders')
            ->join('product_orders', 'orders.id', '=', 'product_orders.order_id')
            ->join('products', 'product_orders.product_id', '=', 'products.id')
            ->select(DB::raw('SUM(orders.qty * products.price) as total_revenue'))
            ->first();
        
        $totalRevenue = $totalRevenueDetailed->total_revenue ?? 0;
        
        // Recent orders with user and product details
        $recentOrders = Order::with(['user', 'products'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($order) {
                // Calculate total amount for this order
                $totalAmount = DB::table('orders')
                    ->join('product_orders', 'orders.id', '=', 'product_orders.order_id')
                    ->join('products', 'product_orders.product_id', '=', 'products.id')
                    ->where('orders.id', $order->id)
                    ->select(DB::raw('SUM(orders.qty * products.price) as total'))
                    ->first();
                
                // Get delivery status from deliveries table
                $deliveryStatus = DB::table('deliveries')
                    ->where('order_id', $order->id)
                    ->value('delivery_status') ?? 'Pending';
                
                return (object)[
                    'id' => $order->id,
                    'order_number' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                    'customer_name' => $order->user->name,
                    'total_amount' => $totalAmount->total ?? 0,
                    'status' => $deliveryStatus,
                    'order_date' => $order->order_date,
                    'qty' => $order->qty
                ];
            });
        
        // Fixed: Top selling products calculation
        $topProducts = Product::with(['brand', 'productType'])
            ->withCount(['orders' => function($query) {
                $query->select(DB::raw('SUM(orders.qty)'));
            }])
            ->get()
            ->map(function ($product) {
                // Calculate total quantity sold for this product
                $totalSold = DB::table('product_orders')
                    ->join('orders', 'product_orders.order_id', '=', 'orders.id')
                    ->where('product_orders.product_id', $product->id)
                    ->sum('orders.qty');
                
                $revenue = $totalSold * $product->price;
                
                return (object)[
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->productType->name ?? 'N/A',
                    'brand' => $product->brand->name ?? 'N/A',
                    'price' => $product->price,
                    'units_sold' => $totalSold,
                    'revenue' => $revenue,
                    'status' => $product->status
                ];
            })
            ->sortByDesc('units_sold')
            ->take(5)
            ->values();
        
        // Fixed: Monthly sales data for chart
        $monthlySales = DB::table('orders')
            ->join('product_orders', 'orders.id', '=', 'product_orders.order_id')
            ->join('products', 'product_orders.product_id', '=', 'products.id')
            ->select(
                DB::raw('MONTH(orders.order_date) as month'),
                DB::raw('YEAR(orders.order_date) as year'),
                DB::raw('SUM(orders.qty * products.price) as total_sales')
            )
            ->whereYear('orders.order_date', date('Y'))
            ->groupBy('year', 'month')
            ->orderBy('month')
            ->get();
        
        // Recent feedback
        $recentFeedbacks = Feedback::with('user')
            ->latest()
            ->take(5)
            ->get();
        
        // Low stock / inactive products
        $inactiveProducts = Product::where('status', 'inactive')->count();
        $activeProducts = Product::where('status', 'active')->count();
        
        // Fixed: Order status distribution
        $orderStatusDistribution = DB::table('deliveries')
            ->select('delivery_status', DB::raw('count(*) as count'))
            ->groupBy('delivery_status')
            ->get();
        
        // Weekly sales trend (last 7 days)
        $weeklySales = DB::table('orders')
            ->join('product_orders', 'orders.id', '=', 'product_orders.order_id')
            ->join('products', 'product_orders.product_id', '=', 'products.id')
            ->select(
                DB::raw('DATE(orders.order_date) as date'),
                DB::raw('SUM(orders.qty * products.price) as daily_sales')
            )
            ->where('orders.order_date', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        // Products by category
        $productsByCategory = ProductType::withCount('products')
            ->having('products_count', '>', 0)
            ->get();
        
        // Recent registered users (last 7 days)
        $recentUsers = User::where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalOrders', 
            'totalProducts',
            'totalFeedbacks',
            'totalBrands',
            'totalProductTypes',
            'activePromotions',
            'totalRevenue',
            'recentOrders',
            'topProducts',
            'monthlySales',
            'recentFeedbacks',
            'inactiveProducts',
            'activeProducts',
            'orderStatusDistribution',
            'weeklySales',
            'productsByCategory',
            'recentUsers'
        ));
    }
}