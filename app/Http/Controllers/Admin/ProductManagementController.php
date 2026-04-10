<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductManagementController extends Controller
{
    /**
     * Display product list
     */
    public function index()
    {
        $products = Product::paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Show create product form
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store new product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku',
            'category' => 'nullable|string|max:100',
            'image_url' => 'nullable|url',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $product = Product::create($validated);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $this->handleImageUploads($product, $request->file('images'));
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Show edit product form
     */
    public function edit(Product $product)
    {
        $product->load('images');
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Update product
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'category' => 'nullable|string|max:100',
            'image_url' => 'nullable|url',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
        ]);

        $product->update($validated);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $this->handleImageUploads($product, $request->file('images'));
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Delete product
     */
    public function destroy(Product $product)
    {
        // Delete product images
        foreach ($product->images as $image) {
            $this->deleteImageFile($image);
        }
        
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully!');
    }

    /**
     * Delete product image
     */
    public function deleteImage(ProductImage $productImage)
    {
        $this->deleteImageFile($productImage);
        
        return redirect()->back()->with('success', 'Image deleted successfully!');
    }

    /**
     * Set primary image
     */
    public function setPrimaryImage(ProductImage $productImage)
    {
        // Reset all images for this product
        ProductImage::where('product_id', $productImage->product_id)
            ->update(['is_primary' => false]);
        
        // Set this image as primary
        $productImage->update(['is_primary' => true]);
        
        return redirect()->back()->with('success', 'Primary image updated successfully!');
    }

    /**
     * Handle multiple image uploads
     */
    private function handleImageUploads(Product $product, array $images): void
    {
        foreach ($images as $index => $image) {
            if ($image->isValid()) {
                $path = $image->store('products/' . $product->id, 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'image_name' => $image->getClientOriginalName(),
                    'sort_order' => $index,
                    'is_primary' => $product->images()->count() === 0, // First image is primary if no images exist
                ]);
            }
        }
    }

    /**
     * Delete image file and record
     */
    private function deleteImageFile(ProductImage $productImage): void
    {
        // Delete file from storage
        if ($productImage->image_path && Storage::disk('public')->exists($productImage->image_path)) {
            Storage::disk('public')->delete($productImage->image_path);
        }
        
        // Delete database record
        $productImage->delete();
    }
}
