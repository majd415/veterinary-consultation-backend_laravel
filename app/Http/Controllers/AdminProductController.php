<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Traits\HandlesImageUploads;

class AdminProductController extends Controller
{
    use HandlesImageUploads; // Added

    public function index()
    {
        $products = Product::with('category')->paginate(10);
        $categories = ProductCategory::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    // --- Product Methods ---

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'name_ar' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:product_categories,id',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $request->image ? $this->uploadImage($request->file('image'), 'products') : null;

        Product::create([
            'name' => ['en' => $request->name, 'ar' => $request->name_ar],
            'description' => ['en' => $request->description ?? '', 'ar' => $request->description_ar ?? ''],
            'price' => $request->price,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'rate' => 0
        ]);

        return redirect()->back()->with('success', 'Product created successfully');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        
        $data = [
            'name' => ['en' => $request->name, 'ar' => $request->name_ar],
            'description' => ['en' => $request->description ?? '', 'ar' => $request->description_ar ?? ''],
            'price' => $request->price,
            'category_id' => $request->category_id,
        ];

        if ($request->hasFile('image')) {
            $this->deleteImage($product->image);
            $data['image'] = $this->uploadImage($request->file('image'), 'products');
        }

        $product->update($data);

        return redirect()->back()->with('success', 'Product updated successfully');
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return redirect()->back()->with('success', 'Product deleted successfully');
    }

    // --- Category Methods (Quick handling via same controller for now) ---
    
    public function storeCategory(Request $request) {
        ProductCategory::create(['name' => ['en' => $request->name, 'ar' => $request->name]]);
        return redirect()->back()->with('success', 'Category created successfully');
    }

    public function destroyCategory($id) {
        ProductCategory::destroy($id);
        return redirect()->back()->with('success', 'Category deleted successfully');
    }
}
