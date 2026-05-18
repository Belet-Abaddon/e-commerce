<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Delivery;
use App\Models\ProductType;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Sales Report
     */
    public function sales(Request $request): View
    {
        $query = Order::with(['user', 'products']);

        // Filter by date range
        if ($request->has('start_date') && $request->input('start_date')) {
            $query->where('order_date', '>=', $request->input('start_date'));
        }
        if ($request->has('end_date') && $request->input('end_date')) {
            $query->where('order_date', '<=', $request->input('end_date'));
        }

        $orders = $query->latest('order_date')->paginate(10);

        // Calculate totals
        $totalOrders = Order::count();
        $totalItems = Order::sum('qty');
        $totalRevenue = 0;

        foreach ($orders as $order) {
            $orderTotal = $order->products->sum(function ($product) use ($order) {
                return $product->price * $order->qty;
            });
            $order->order_total = $orderTotal;
            $totalRevenue += $orderTotal;
        }

        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Monthly sales summary for chart
        $monthlySales = DB::table('orders')
            ->join('product_orders', 'orders.id', '=', 'product_orders.order_id')
            ->join('products', 'product_orders.product_id', '=', 'products.id')
            ->select(
                DB::raw('YEAR(orders.order_date) as year'),
                DB::raw('MONTH(orders.order_date) as month'),
                DB::raw('SUM(orders.qty * products.price) as total_sales')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $chartLabels = [];
        $chartData = [];
        foreach ($monthlySales as $sale) {
            $chartLabels[] = date('M Y', mktime(0, 0, 0, $sale->month, 1, $sale->year));
            $chartData[] = $sale->total_sales;
        }

        return view('admin.reports.sales', compact('orders', 'totalOrders', 'totalRevenue', 'totalItems', 'averageOrderValue', 'chartLabels', 'chartData'));
    }

    /**
     * Products Report
     */
    public function products(Request $request): View
    {
        $products = Product::with(['productType', 'brand'])->paginate(10);

        // Calculate sales data for each product
        foreach ($products as $product) {
            $product->total_sold = DB::table('product_orders')
                ->join('orders', 'product_orders.order_id', '=', 'orders.id')
                ->where('product_orders.product_id', $product->id)
                ->sum('orders.qty');

            $product->total_revenue = $product->total_sold * $product->price;
        }

        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->count();
        $inactiveProducts = Product::where('status', 'inactive')->count();
        $outOfStockProducts = Product::where('status', 'out_of_stock')->count();
        $totalStockValue = Product::sum('price');

        // Top 5 products for chart
        $topProducts = Product::with(['productType', 'brand'])
            ->get()
            ->map(function ($product) {
                $product->total_sold = DB::table('product_orders')
                    ->join('orders', 'product_orders.order_id', '=', 'orders.id')
                    ->where('product_orders.product_id', $product->id)
                    ->sum('orders.qty');
                return $product;
            })
            ->sortByDesc('total_sold')
            ->take(5);

        $topProductsLabels = $topProducts->pluck('name')->toArray();
        $topProductsData = $topProducts->pluck('total_sold')->toArray();

        $productTypes = ProductType::all();
        $brands = Brand::all();

        return view('admin.reports.products', compact(
            'products',
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'outOfStockProducts',
            'totalStockValue',
            'topProducts',
            'topProductsLabels',
            'topProductsData',
            'productTypes',
            'brands'
        ));
    }

    /**
     * Customers Report
     */
    public function customers(Request $request): View
    {
        $customers = User::with(['orders'])->paginate(10);

        $totalCustomers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalUsers = User::where('role', 'user')->count();

        foreach ($customers as $customer) {
            $customer->total_orders = $customer->orders->count();
            $customer->total_spent = $customer->orders->sum(function ($order) {
                return $order->products->sum(function ($product) use ($order) {
                    return $product->price * $order->qty;
                });
            });
        }

        // Monthly customer registration
        $monthlyRegistrations = User::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        $registrationLabels = [];
        $registrationData = [];
        foreach ($monthlyRegistrations as $reg) {
            $registrationLabels[] = date('M Y', mktime(0, 0, 0, $reg->month, 1, $reg->year));
            $registrationData[] = $reg->count;
        }

        return view('admin.reports.customers', compact('customers', 'totalCustomers', 'totalAdmins', 'totalUsers', 'registrationLabels', 'registrationData'));
    }

    /**
     * Inventory Report
     */
    public function inventory(Request $request): View
    {
        $products = Product::with(['productType', 'brand'])->paginate(10);

        $totalProducts = Product::count();
        $activeProducts = Product::where('status', 'active')->count();
        $inactiveProducts = Product::where('status', 'inactive')->count();
        $totalInventoryValue = Product::sum('price');

        $productsByType = ProductType::withCount('products')->get();
        $categoryLabels = $productsByType->pluck('name')->toArray();
        $categoryData = $productsByType->pluck('products_count')->toArray();

        $productsByBrand = Brand::withCount('products')->get();
        $brandLabels = $productsByBrand->pluck('name')->toArray();
        $brandData = $productsByBrand->pluck('products_count')->toArray();

        return view('admin.reports.inventory', compact('products', 'totalProducts', 'activeProducts', 'inactiveProducts', 'totalInventoryValue', 'categoryLabels', 'categoryData', 'brandLabels', 'brandData', 'productsByType', 'productsByBrand'));
    }

    /**
     * Delivery Report
     */
    public function deliveries(Request $request): View
    {
        $deliveries = Delivery::with(['order', 'order.user'])->latest()->paginate(10);

        $statusCounts = [
            'Pending' => Delivery::where('delivery_status', 'Pending')->count(),
            'Processing' => Delivery::where('delivery_status', 'Processing')->count(),
            'Shipped' => Delivery::where('delivery_status', 'Shipped')->count(),
            'Delivered' => Delivery::where('delivery_status', 'Delivered')->count(),
            'Cancelled' => Delivery::where('delivery_status', 'Cancelled')->count(),
        ];

        $statusLabels = array_keys($statusCounts);
        $statusData = array_values($statusCounts);

        $totalDeliveries = Delivery::count();
        $completedDeliveries = Delivery::where('delivery_status', 'Delivered')->count();
        $pendingDeliveries = Delivery::whereIn('delivery_status', ['Pending', 'Processing', 'Shipped'])->count();
        $completionRate = $totalDeliveries > 0 ? ($completedDeliveries / $totalDeliveries) * 100 : 0;

        return view('admin.reports.deliveries', compact('deliveries', 'statusCounts', 'totalDeliveries', 'completedDeliveries', 'pendingDeliveries', 'completionRate', 'statusLabels', 'statusData'));
    }
}
