<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * Display all orders with pagination (10 per page)
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'products']);
        
        // Search by customer name or email
        if ($request->has('search') && $request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter by order date
        if ($request->has('order_date') && $request->order_date) {
            $query->whereDate('order_date', $request->order_date);
        }
        
        // Paginate with 10 items per page
        $orders = $query->latest('order_date')->paginate(10);
        
        // Calculate total amount for each order from product_orders
        foreach ($orders as $order) {
            $order->total_amount = $order->products->sum(function($product) use ($order) {
                return $product->price * $order->qty;
            });
        }
        
        return view('admin.orders.index', compact('orders'));
    }
    
    /**
     * Display order details
     */
    public function show(int $id): View
    {
        $order = Order::with(['user', 'products', 'products.images', 'products.productType', 'products.brand'])
            ->findOrFail($id);
        
        // Calculate total amount
        $order->total_amount = $order->products->sum(function($product) use ($order) {
            return $product->price * $order->qty;
        });
        
        // Get all products for the order
        $products = $order->products;
        
        return view('admin.orders.show', compact('order', 'products'));
    }
    
    /**
     * Delete order
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();
            
            $order = Order::findOrFail($id);
            
            // Delete related records from product_orders (pivot table)
            $order->products()->detach();
            
            // Delete the order
            $order->delete();
            
            DB::commit();
            
            return redirect()->route('admin.orders.index')
                ->with('success', 'Order deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }
}