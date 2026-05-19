<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class PromotionController extends Controller
{
    /**
     * Display all promotions (Year End, New Year, etc.)
     */
    public function index(Request $request): View
    {
        $query = Promotion::query();
        
        // Search by promotion name
        if ($request->has('search') && $request->search) {
            $query->where('promotion_name', 'like', '%' . $request->search . '%');
        }
        
        $promotions = $query->latest()->paginate(10);
        
        return view('admin.promotions.index', compact('promotions'));
    }
    
    /**
     * Show create promotion form
     */
    public function create(): View
    {
        return view('admin.promotions.create');
    }
    
    /**
     * Store a new promotion
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'promotion_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        Promotion::create([
            'promotion_name' => $request->promotion_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        
        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion created successfully!');
    }
    
    /**
     * Show edit promotion form
     */
    public function edit(int $id): View
    {
        $promotion = Promotion::findOrFail($id);
        return view('admin.promotions.edit', compact('promotion'));
    }
    
    /**
     * Update promotion
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'promotion_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);
        
        $promotion = Promotion::findOrFail($id);
        $promotion->update([
            'promotion_name' => $request->promotion_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);
        
        return redirect()->route('admin.promotions.index')
            ->with('success', 'Promotion updated successfully!');
    }
    
    /**
     * Delete promotion
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $promotion = Promotion::findOrFail($id);
            $promotion->delete();
            
            return redirect()->route('admin.promotions.index')
                ->with('success', 'Promotion deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete promotion: ' . $e->getMessage());
        }
    }
    
    /**
     * Display promotion products for a specific promotion
     */
    public function products(int $promotionId): View
    {
        $promotion = Promotion::with(['products'])->findOrFail($promotionId);
        $allProducts = Product::where('status', 'active')->get();
        
        return view('admin.promotions.products', compact('promotion', 'allProducts'));
    }
    
    /**
     * Add product to promotion
     */
    public function addProduct(Request $request, int $promotionId): RedirectResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);
        
        $promotion = Promotion::findOrFail($promotionId);
        
        // Check if product already exists in this promotion
        if ($promotion->products()->where('product_id', $request->product_id)->exists()) {
            return redirect()->back()->with('error', 'Product already exists in this promotion!');
        }
        
        $promotion->products()->attach($request->product_id, [
            'percentage' => $request->percentage,
            'description' => $request->description,
        ]);
        
        return redirect()->route('admin.promotions.products', $promotionId)
            ->with('success', 'Product added to promotion successfully!');
    }
    
    /**
     * Update promotion product
     */
    public function updateProduct(Request $request, int $promotionId, int $productId): RedirectResponse
    {
        $request->validate([
            'percentage' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);
        
        $promotion = Promotion::findOrFail($promotionId);
        
        $promotion->products()->updateExistingPivot($productId, [
            'percentage' => $request->percentage,
            'description' => $request->description,
        ]);
        
        return redirect()->route('admin.promotions.products', $promotionId)
            ->with('success', 'Product updated successfully!');
    }
    
    /**
     * Delete product from promotion
     */
    public function deleteProduct(int $promotionId, int $productId): RedirectResponse
    {
        $promotion = Promotion::findOrFail($promotionId);
        $promotion->products()->detach($productId);
        
        return redirect()->route('admin.promotions.products', $promotionId)
            ->with('success', 'Product removed from promotion successfully!');
    }
}