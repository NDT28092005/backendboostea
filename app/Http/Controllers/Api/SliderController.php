<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    public function index()
    {
        $sliders = Slider::orderBy('order')->get()->map(function ($slider) {
            $slider->image_url = asset($slider->image_url);
            return $slider;
        });

        return response()->json([
            "data" => $sliders
        ]);
    }

    public function show($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->image_url = asset($slider->image_url);

        return response()->json([
            "data" => $slider
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096',
        ]);

        $path = $request->file('image')->store('sliders', 'public');

        $slider = Slider::create([
            'title'        => $request->title,
            'redirect_url' => $request->redirect_url,
            'order'        => $request->order ?? 0,
            'image_url'    => "storage/" . $path,  // ✅ lưu vào DB
        ]);

        // Trả URL đầy đủ cho React render
        $slider->image_url = asset($slider->image_url);

        return $slider;
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::findOrFail($id);

        if ($request->hasFile('image')) {
            // xóa ảnh cũ
            $oldPath = str_replace("storage/", "", $slider->image_url);
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('sliders', 'public');
            $slider->image_url = "storage/" . $path;
        }

        $slider->title = $request->title;
        $slider->redirect_url = $request->redirect_url;
        $slider->order = $request->order ?? $slider->order;
        $slider->save();

        $slider->image_url = asset($slider->image_url);

        return $slider;
    }

    public function destroy($id)
    {
        $slider = Slider::findOrFail($id);

        $oldPath = str_replace("storage/", "", $slider->image_url);
        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        $slider->delete();

        return response()->json(['success' => true]);
    }
    public function homepageSliders()
    {
        $sliders = Slider::orderBy("order", "asc")->get()->map(function ($slider) {
            $slider->image_url = asset($slider->image_url); // ✅ convert thành URL đầy đủ
            return $slider;
        });

        return response()->json([
            "data" => $sliders
        ]);
    }
}
