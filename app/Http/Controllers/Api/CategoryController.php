<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // ✅ Lấy danh sách category (full URL ảnh)
    public function index()
    {
        return response()->json(
            Category::orderBy('id', 'ASC')->get()
        );
    }

    // ✅ Xem 1 category
    public function show($id)
    {
        return response()->json(Category::findOrFail($id));
    }

    // ✅ Thêm category + upload ảnh
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $imageUrl = asset(Storage::url($path));
        }

        $category = Category::create([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'image_url' => $imageUrl,
        ]);

        return response()->json([
            'message'  => 'Thêm category thành công',
            'category' => $category
        ]);
    }

    // ✅ Update category + update ảnh
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'  => 'required',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $category = Category::findOrFail($id);
        $imageUrl = $category->image_url; // giữ ảnh cũ nếu không upload mới

        if ($request->hasFile('image')) {

            // Xóa ảnh cũ nếu tồn tại
            if ($category->image_url) {
                $oldImage = str_replace(asset('storage') . '/', '', $category->image_url);
                Storage::disk('public')->delete($oldImage);
            }

            $path = $request->file('image')->store('categories', 'public');
            $imageUrl = asset(Storage::url($path));
        }

        $category->update([
            'name'      => $request->name,
            'slug'      => Str::slug($request->name),
            'image_url' => $imageUrl
        ]);

        return response()->json([
            'message'  => 'Cập nhật category thành công',
            'category' => $category
        ]);
    }

    // ✅ Xóa category + ảnh
    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        if ($category->image_url) {
            $oldImage = str_replace(asset('storage') . '/', '', $category->image_url);
            Storage::disk('public')->delete($oldImage);
        }

        $category->delete();

        return response()->json(['message' => 'Xóa category thành công']);
    }
}
