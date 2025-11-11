<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use App\Models\User;

class ProductReviewController extends Controller
{
    public function adminCreate(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required',
        ]);

        $user = User::find($validated['user_id']);

        ProductReview::create([
            'product_id' => $validated['product_id'],
            'user_id' => $validated['user_id'],
            'user_name' => $user->name,  // ✅ lấy đúng tên user
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => true
        ]);

        return response()->json(['message' => '✅ Review đã được thêm!']);
    }
    public function store(Request $request)
    {
        if (!auth()->check()) {
            return response()->json(['message' => 'Bạn phải đăng nhập trước khi đánh giá'], 401);
        }

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required'
        ]);

        ProductReview::create([
            'product_id' => $validated['product_id'],
            'user_id' => auth()->id(),
            'user_name' => auth()->user()->name,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'is_approved' => false,
        ]);

        return response()->json([
            'message' => 'Cảm ơn bạn đã bình luận! Chúng tôi sẽ duyệt trong thời gian sớm nhất.'
        ]);
    }
    // Lấy danh sách review theo product_id
    public function listByProduct(Request $request, $productId)
    {
        $query = ProductReview::where('product_id', $productId)
            ->with(['user:id,name,avatar'])
            ->orderBy('created_at', 'desc');
        if (!$request->has('all')) {
            $query->where('is_approved', true);
        }

        $reviews = $query->get()->map(function ($review) {
            $review->user_avatar_url = $review->user?->avatar
                ? (str_contains($review->user->avatar, 'http')
                    ? $review->user->avatar
                    : asset('storage/' . $review->user->avatar))
                : asset('default-avatar.png');

            return $review;
        });

        return response()->json($reviews);
    }

    // Duyệt review
    public function approve($id)
    {
        ProductReview::where('id', $id)->update(['is_approved' => true]);

        return response()->json(['message' => 'Review approved ✅']);
    }

    // Reject review
    public function reject($id)
    {
        ProductReview::where('id', $id)->update(['is_approved' => false]);

        return response()->json(['message' => 'Review rejected 🚫']);
    }

    // Xóa review
    public function destroy($id)
    {
        ProductReview::findOrFail($id)->delete();

        return response()->json(['message' => 'Review deleted 🗑️']);
    }
}
