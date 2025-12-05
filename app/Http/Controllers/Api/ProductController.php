<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->orderBy('id', 'desc');

        if ($request->category_id && $request->category_id !== "all") {
            $query->where('category_id', $request->category_id);
        }

        return $query->paginate(9);
    }


    public function show($id)
    {
        $product = Product::with(['category', 'images', 'reviews'])->findOrFail($id);
        return response()->json($product);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:products,slug',
            'price' => 'required|integer',
            'original_price' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'stock' => 'required|integer',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'description' => 'nullable|string',   // ✅ validate mô tả (TEXT field - no max limit)
        ]);

        // ✅ tạo product trước
        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $product = Product::create($validated);

        // ✅ upload ảnh chính
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->update(['image_url' => asset(Storage::url($path))]);
        }

        // ✅ upload ảnh phụ (gallery)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => asset(Storage::url($path)),
                ]);
            }
        }

        return response()->json(['message' => 'Created', 'data' => $product], 201);
    }


    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'original_price' => 'nullable|integer', // ✅ FIX
            'featured' => 'nullable|boolean',       // ✅ FIX
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:2048',
            'description' => 'nullable|string',   // ✅ validate mô tả (TEXT field - no max limit)
        ]);

        $validated['slug'] = Str::slug($request->name); // ✅ create slug luôn

        $product->update($validated);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->update(['image_url' => asset(Storage::url($path))]);
        }

        if ($request->hasFile('images')) {
            ProductImage::where('product_id', $product->id)->delete();

            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => asset(Storage::url($path))
                ]);
            }
        }

        return response()->json(['message' => 'Updated', 'data' => $product]);
    }



    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // xoá ảnh chính
        if ($product->image_url) {
            $oldPath = str_replace('/storage/', '', $product->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        // Xóa ảnh gallery phụ
        ProductImage::where('product_id', $product->id)->delete();

        $product->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
