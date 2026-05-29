<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PaymentController extends Controller
{
    /**
     * Display all payments with pagination (10 per page)
     */
    public function index(Request $request): View
    {
        $query = Payment::with(['order', 'order.user']);
        
        // Search by payment name
        if ($request->has('search') && $request->search) {
            $query->where('payment_name', 'like', '%' . $request->search . '%');
        }
        
        // Filter by payment type
        if ($request->has('payment_type') && $request->payment_type != '') {
            $query->where('payment_type', $request->payment_type);
        }
        
        // Paginate with 10 items per page
        $payments = $query->latest()->paginate(10);
        
        // Get payment type counts
        $typeCounts = [
            'all' => Payment::count(),
            'bank_transfer' => Payment::where('payment_type', 'bank_transfer')->count(),
            'credit_card' => Payment::where('payment_type', 'credit_card')->count(),
            'cash_on_delivery' => Payment::where('payment_type', 'cash_on_delivery')->count(),
            'e_wallet' => Payment::where('payment_type', 'e_wallet')->count(),
        ];
        
        return view('admin.payments.index', compact('payments', 'typeCounts'));
    }
    
    /**
     * Show payment details
     */
    public function show(int $id): View
    {
        $payment = Payment::with(['order', 'order.user', 'order.products'])->findOrFail($id);
        
        // Calculate order total
        $orderTotal = $payment->order->products->sum(function($product) use ($payment) {
            return $product->price * $payment->order->qty;
        });
        
        return view('admin.payments.show', compact('payment', 'orderTotal'));
    }
    
    /**
     * Delete payment
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $payment = Payment::findOrFail($id);
            
            // Delete screenshot file if exists
            if ($payment->screenshot && file_exists(storage_path('app/public/' . $payment->screenshot))) {
                unlink(storage_path('app/public/' . $payment->screenshot));
            }
            
            $payment->delete();
            
            return redirect()->route('admin.payments.index')
                ->with('success', 'Payment deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete payment: ' . $e->getMessage());
        }
    }
}