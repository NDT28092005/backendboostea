<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gift;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GiftController extends Controller
{
    // List (with optional pagination)
    public function index(Request $request)
    {
        $query = Gift::query()->with('category')->orderBy('id', 'asc');

        if ($request->has('q')) {
            $q = $request->get('q');
            $query->where('name', 'like', "%{$q}%");
        }

        $perPage = (int) $request->get('per_page', 12);
        $data = $query->paginate($perPage);

        return response()->json($data);
    }

    // Show single
    public function show($id)
    {
        $gift = Gift::with('category')->findOrFail($id);
        return response()->json($gift);
    }

    // Store new gift (multipart/form-data)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:gifts,slug',
            'price' => 'required|integer',
            'original_price' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048' // 2MB
        ]);

        // handle image
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('gifts', 'public');
            $validated['image_url'] = asset(Storage::url($path)); // /storage/gifts/...
        }

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $gift = Gift::create($validated);

        return response()->json(['message' => 'Created', 'data' => $gift], 201);
    }

    // Update existing
    public function update(Request $request, $id)
    {
        $gift = Gift::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'slug' => ['nullable','string','max:150', Rule::unique('gifts','slug')->ignore($gift->id)],
            'price' => 'required|integer',
            'original_price' => 'nullable|integer',
            'featured' => 'nullable|boolean',
            'category_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($gift->image_url) {
                $oldPath = str_replace('/storage/', '', $gift->image_url);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $path = $request->file('image')->store('gifts', 'public');
            $validated['image_url'] = asset(Storage::url($path));
        }

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $gift->update($validated);

        return response()->json(['message' => 'Updated', 'data' => $gift]);
    }

    // Delete
    public function destroy($id)
    {
        $gift = Gift::findOrFail($id);

        if ($gift->image_url) {
            $oldPath = str_replace('/storage/', '', $gift->image_url);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $gift->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
