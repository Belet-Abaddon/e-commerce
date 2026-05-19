<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductType;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'productType']);

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Filter by brand
        if ($request->has('brand_id') && !empty($request->brand_id)) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filter by product type
        if ($request->has('product_type_id') && !empty($request->product_type_id)) {
            $query->where('product_type_id', $request->product_type_id);
        }

        // Sort functionality
        $sort = $request->get('sort', 'created_at');
        $order = $request->get('order', 'desc');
        $query->orderBy($sort, $order);

        $products = $query->paginate(10)->withQueryString();

        // Get data for filters
        $brands = Brand::all();
        $productTypes = ProductType::all();

        return view('admin.products.index', compact('products', 'brands', 'productTypes'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $brands = Brand::all();
        $productTypes = ProductType::all();

        return view('admin.products.create', compact('brands', 'productTypes'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'product_type_id' => 'required|exists:product_types,id',
            'brand_id' => 'required|exists:brands,id',
            'status' => 'required|in:active,inactive,out_of_stock',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name', 'description', 'price', 'product_type_id', 'brand_id', 'status']);
        $product = Product::create($data);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $images = $request->file('images');

            $validImages = array_filter($images, function ($image) {
                return $image !== null && $image->isValid();
            });

            foreach ($validImages as $image) {
                $imagePath = $image->store('products', 'public');
                $product->images()->create([
                    'image_path' => $imagePath
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully! ' . $product->images->count() . ' images uploaded.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['brand', 'productType', 'images', 'orders']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $brands = Brand::all();
        $productTypes = ProductType::all();
        $product->load('images');

        return view('admin.products.edit', compact('product', 'brands', 'productTypes'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'product_type_id' => 'required|exists:product_types,id',
            'brand_id' => 'required|exists:brands,id',
            'status' => 'required|in:active,inactive,out_of_stock',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only(['name', 'description', 'price', 'product_type_id', 'brand_id', 'status']);
        $product->update($data);

        // Handle new image additions cleanly during update cycle
        if ($request->hasFile('images')) {
            $images = $request->file('images');

            if (is_array($images)) {
                foreach ($images as $image) {
                    if ($image && $image->isValid()) {
                        $imagePath = $image->store('products', 'public');
                        $product->images()->create([
                            'image_path' => $imagePath
                        ]);
                    }
                }
            }
        }

        return redirect()->route('admin.products.edit', $product->id)
            ->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Delete a specific image from product.
     */
    public function deleteImage($id)
    {
        // Safely extract isolated target model structure
        $image = Image::find($id);
        
        if (!$image) {
            return redirect()->back()->with('error', 'Image item target record could not be found.');
        }
        
        $productId = $image->product_id;
        
        // Unlink storage item trace reference
        if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        // Wipe target trace from database schema mapping
        $image->delete();
        
        // Safety verification logic trace validation check 
        $product = Product::find($productId);
        if (!$product) {
            Log::error('Product disappeared after image deletion process checkpoint sequence validation! ID: ' . $productId);
            return redirect()->route('admin.products.index')
                ->with('error', 'Critical failure error: Product master sequence mapping collapsed during target child record drop action.');
        }
        
        return redirect()->route('admin.products.edit', $productId)
            ->with('success', 'Image was successfully unlinked from product catalog gallery grid!');
    }
    

    /**
     * Toggle product status.
     */
    public function toggleStatus(Product $product)
    {
        $statuses = ['active', 'inactive', 'out_of_stock'];
        $currentIndex = array_search($product->status, $statuses);
        $nextIndex = ($currentIndex + 1) % count($statuses);
        $product->status = $statuses[$nextIndex];
        $product->save();

        return response()->json([
            'success' => true,
            'status' => $product->status,
            'message' => 'Product status updated successfully!'
        ]);
    }
}