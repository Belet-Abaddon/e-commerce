<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ProductTypeController extends Controller
{
    /**
     * Display a listing of product types.
     */
    public function index(Request $request)
    {
        $query = ProductType::query();
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);
        
        $productTypes = $query->paginate(10)->withQueryString();
        
        return view('admin.product-types.index', compact('productTypes'));
    }

    /**
     * Show the form for creating a new product type.
     */
    public function create()
    {
        return view('admin.product-types.create');
    }

    /**
     * Store a newly created product type in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:product_types',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        ProductType::create($request->only(['name', 'description']));

        return redirect()->route('admin.product-types.index')
            ->with('success', 'Product type created successfully!');
    }

    /**
     * Display the specified product type.
     */
    public function show(ProductType $productType)
    {
        $productType->load('products');
        return view('admin.product-types.show', compact('productType'));
    }

    /**
     * Show the form for editing the specified product type.
     */
    public function edit(ProductType $productType)
    {
        return view('admin.product-types.edit', compact('productType'));
    }

    /**
     * Update the specified product type in storage.
     */
    public function update(Request $request, ProductType $productType)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:product_types,name,' . $productType->id,
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $productType->update($request->only(['name', 'description']));

        return redirect()->route('admin.product-types.index')
            ->with('success', 'Product type updated successfully!');
    }

    /**
     * Remove the specified product type from storage.
     */
    public function destroy(ProductType $productType)
    {
        // Check if there are products using this type
        if ($productType->products()->count() > 0) {
            return redirect()->route('admin.product-types.index')
                ->with('error', 'Cannot delete this product type because it has ' . $productType->products()->count() . ' product(s) associated with it.');
        }
        
        $productType->delete();

        return redirect()->route('admin.product-types.index')
            ->with('success', 'Product type deleted successfully!');
    }
}