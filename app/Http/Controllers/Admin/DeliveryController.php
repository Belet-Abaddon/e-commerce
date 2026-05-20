<?php

namespace App\Http\Controllers\Admin;

use App\Models\Delivery;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\DeliveryStatusMail;

class DeliveryController extends Controller
{
    /**
     * Get promotion for a product at a specific date.
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
     * Display a listing of deliveries.
     */
    public function index(Request $request)
    {
        $query = Delivery::with(['order.user']);

        // Search functionality
        if ($request->has('search') && !empty($request->input('search'))) {
            $query->whereHas('order', function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->input('search') . '%')
                    ->orWhereHas('user', function ($u) use ($request) {
                        $u->where('name', 'like', '%' . $request->input('search') . '%')
                            ->orWhere('email', 'like', '%' . $request->input('search') . '%');
                    });
            });
        }

        // Filter by delivery status
        if ($request->has('delivery_status') && !empty($request->input('delivery_status'))) {
            $query->where('delivery_status', $request->input('delivery_status'));
        }

        // Filter by date range
        if ($request->has('date_from') && !empty($request->input('date_from'))) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }

        if ($request->has('date_to') && !empty($request->input('date_to'))) {
            $query->whereDate('created_at', '<=', $request->input('date_to'));
        }

        // Sort functionality
        $sort = $request->input('sort', 'created_at');
        $order = $request->input('order', 'desc');
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

        // Calculate promotion prices for each product in the order
        $orderDate = $delivery->order->order_date;
        $calculatedRunningTotal = 0;

        foreach ($delivery->order->products as $product) {
            // Get discounted price at order date
            $discountedPrice = $this->getDiscountedPriceAtDate($product->price, $product->id, $orderDate);
            $hasPromotion = ($discountedPrice < $product->price);
            $discountPercentage = $hasPromotion ? round((($product->price - $discountedPrice) / $product->price) * 100, 0) : 0;

            // Add properties to product for view
            $product->price_at_order = $discountedPrice;
            $product->has_promotion_at_order = $hasPromotion;
            $product->discount_percentage = $discountPercentage;

            $calculatedRunningTotal += $discountedPrice * ($product->pivot->qty ?? 1);
        }

        return view('admin.deliveries.show', compact('delivery', 'calculatedRunningTotal'));
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
        $newStatus = $request->delivery_status;

        // Update status
        $delivery->delivery_status = $newStatus;
        $delivery->save();

        // Send email only when status changes to 'in_progress' or 'delivered'
        if (($newStatus == 'in_progress' && $oldStatus != 'in_progress') ||
            ($newStatus == 'delivered' && $oldStatus != 'delivered')
        ) {

            try {
                $userEmail = $delivery->order->user->email;
                if ($userEmail) {
                    Mail::to($userEmail)->send(new DeliveryStatusMail($delivery, $oldStatus, $newStatus));

                    // Log success
                    Log::info('Delivery status email sent successfully to: ' . $userEmail);
                } else {
                    Log::warning('No email found for user: ' . $delivery->order->user_id);
                }
            } catch (\Exception $e) {
                Log::error('Failed to send delivery status email: ' . $e->getMessage());
            }
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Delivery status updated successfully!',
                'old_status' => $oldStatus,
                'new_status' => $delivery->delivery_status,
                'email_sent' => ($newStatus == 'in_progress' || $newStatus == 'delivered')
            ]);
        }

        return redirect()->back()->with('success', 'Delivery status updated successfully!');
    }

    /**
     * Send delivery notification email manually.
     */
    public function sendEmail($id)
    {
        try {
            $delivery = Delivery::with(['order.user'])->findOrFail($id);
            
            if (!$delivery->order->user->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Customer email not found.'
                ]);
            }
            
            Mail::to($delivery->order->user->email)->send(new DeliveryStatusMail($delivery, $delivery->delivery_status, $delivery->delivery_status));
            
            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully to ' . $delivery->order->user->email
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send delivery email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Bulk update delivery status.
     */
    public function bulkUpdate(Request $request)
    {
        $ids = $request->input('ids', []);
        $newStatus = $request->input('delivery_status');

        if (empty($ids)) {
            return redirect()->back()->with('error', 'No deliveries selected.');
        }

        if (!$newStatus || !in_array($newStatus, ['pending', 'in_progress', 'delivered'])) {
            return redirect()->back()->with('error', 'Please select a valid status.');
        }

        $updatedCount = 0;

        foreach ($ids as $id) {
            $delivery = Delivery::find($id);
            if ($delivery) {
                $oldStatus = $delivery->delivery_status;

                // Only update if status is different
                if ($delivery->delivery_status != $newStatus) {
                    $delivery->delivery_status = $newStatus;
                    $delivery->save();
                    $updatedCount++;

                    // Send email for each delivery that changed to in_progress or delivered
                    if (($newStatus == 'in_progress' && $oldStatus != 'in_progress') ||
                        ($newStatus == 'delivered' && $oldStatus != 'delivered')
                    ) {
                        try {
                            Mail::to($delivery->order->user->email)->send(new DeliveryStatusMail($delivery, $oldStatus, $newStatus));
                        } catch (\Exception $e) {
                            Log::error('Failed to send bulk update email: ' . $e->getMessage());
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.deliveries.index')
            ->with('success', $updatedCount . ' deliveries updated successfully!');
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