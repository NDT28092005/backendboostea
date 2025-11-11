<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    public function index()
    {
        return Testimonial::orderBy('id', 'desc')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('public/testimonials');
            $validated['avatar_url'] = asset(Storage::url($path));
        }

        Testimonial::create($validated);

        return response()->json(['message' => 'Testimonial created']);
    }

    public function show($id)
    {
        return Testimonial::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $testimonial = Testimonial::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        if ($request->hasFile('avatar')) {

            if ($testimonial->avatar_url) {
                $publicPath = str_replace(asset('/'), 'public/', $testimonial->avatar_url);
                Storage::delete($publicPath);
            }

            $path = $request->file('avatar')->store('public/testimonials');
            $validated['avatar_url'] = asset(Storage::url($path));
        }

        $testimonial->update($validated);

        return response()->json(['message' => 'Testimonial updated']);
    }

    public function destroy($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        if ($testimonial->avatar_url) {
            $publicPath = str_replace(asset('/'), 'public/', $testimonial->avatar_url);
            Storage::delete($publicPath);
        }

        $testimonial->delete();

        return response()->json(['message' => 'Deleted']);
    }
}
