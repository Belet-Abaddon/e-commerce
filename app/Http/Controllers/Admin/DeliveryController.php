<?php

namespace App\Http\Controllers\Admin;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DeliveryController extends Controller
{
    /**
     * Display a listing of deliveries.
     */
    public function index(Request $request)
    {
        $query = Delivery::with(['order.user']);
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->whereHas('order', function($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%') 
                  ->orWhereHas('user', function($u) use ($request) {
                      $u->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        // Filter by delivery status
        if ($request->has('delivery_status') && !empty($request->delivery_status)) {
            $query->where('delivery_status', $request->delivery_status);
        }
        
        // Filter by date range
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);
        
        $deliveries = $query->paginate(15)->withQueryString();
        
        // Get statistics for 3 statuses
        $totalDeliveries = Delivery::count();
        $pendingDeliveries = Delivery::where('delivery_status', 'pending')->count();
        $inProgressDeliveries = Delivery::where('delivery_status', 'in_progress')->count();
        $deliveredDeliveries = Delivery::where('delivery_status', 'delivered')->count();
        
        $recentDeliveries = Delivery::with('order.user')->orderBy('created_at', 'desc')->limit(10)->get();
        
        return view('admin.deliveries.index', compact(
            'deliveries', 
            'totalDeliveries', 
            'pendingDeliveries', 
            'inProgressDeliveries',
            'deliveredDeliveries',
            'recentDeliveries'
        ));
    }

    /**
     * Display the specified delivery.
     */
    public function show(Delivery $delivery)
    {
        $delivery->load(['order.user', 'order.products', 'order.payments']);
        return view('admin.deliveries.show', compact('delivery'));
    }

    /**
     * Update delivery status.
     */
    public function updateStatus(Request $request, Delivery $delivery)
    {
        $request->validate([
            'delivery_status' => 'required|in:pending,in_progress,delivered'
        ]);
        
        $oldStatus = $delivery->delivery_status;
        $delivery->delivery_status = $request->delivery_status;
        $delivery->save();
        
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Delivery status updated successfully!',
                'old_status' => $oldStatus,
                'new_status' => $delivery->delivery_status
            ]);
        }
        
        return redirect()->back()->with('success', 'Delivery status updated successfully!');
    }

    /**
     * Bulk update delivery status.
     */
    public function bulkUpdate(Request $request)
    {
        $ids = $request->input('ids', []);
        $status = $request->input('delivery_status');
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No deliveries selected.');
        }
        
        if (!$status || !in_array($status, ['pending', 'in_progress', 'delivered'])) {
            return redirect()->back()->with('error', 'Please select a valid status.');
        }
        
        Delivery::whereIn('id', $ids)->update(['delivery_status' => $status]);
        
        return redirect()->route('admin.deliveries.index')
            ->with('success', count($ids) . ' deliveries updated successfully!');
    }

    /**
     * Get delivery tracking info.
     */
    public function getTrackingInfo(Delivery $delivery)
    {
        $trackingHistory = [
            [
                'status' => 'Order Placed',
                'date' => $delivery->order->created_at ?? $delivery->created_at,
                'description' => 'Your order has been placed and is being processed.',
                'completed' => true
            ],
            [
                'status' => 'Pending',
                'date' => $delivery->delivery_status == 'pending' ? $delivery->updated_at : $delivery->created_at,
                'description' => 'Your delivery is pending and waiting to be processed.',
                'completed' => in_array($delivery->delivery_status, ['in_progress', 'delivered'])
            ]
        ];
        
        if ($delivery->delivery_status == 'in_progress' || $delivery->delivery_status == 'delivered') {
            $trackingHistory[] = [
                'status' => 'In Progress',
                'date' => $delivery->delivery_status == 'in_progress' ? $delivery->updated_at : $delivery->created_at,
                'description' => 'Your order is being prepared and will be shipped soon.',
                'completed' => $delivery->delivery_status == 'delivered'
            ];
        }
        
        if ($delivery->delivery_status == 'delivered') {
            $trackingHistory[] = [
                'status' => 'Delivered',
                'date' => $delivery->updated_at,
                'description' => 'Your order has been delivered successfully!',
                'completed' => true
            ];
        }
        
        return response()->json([
            'success' => true,
            'current_status' => $delivery->delivery_status,
            'tracking_history' => $trackingHistory
        ]);
    }
}