<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Testimonial;

class HomeApiController extends Controller
{
    public function featuredGifts() // thực ra giờ là products
    {
        return response()->json([
            'status' => true,
            'data' => Product::where('featured', true)
                ->select('id', 'name', 'slug', 'price', 'original_price', 'image_url')
                ->take(10)
                ->get()
        ]);
    }

    public function categories()
    {
        return response()->json([
            'status' => true,
            'data' => Category::select('id', 'name', 'slug', 'image_url')->get()
        ]);
    }

    public function testimonials()
    {
        return response()->json([
            'status' => true,
            'data' => Testimonial::orderBy('id', 'desc')->take(10)->get()
        ]);
    }
}
