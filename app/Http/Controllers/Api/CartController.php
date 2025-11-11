<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /// Lấy giỏ hàng
    public function getCart()
    {
        $cart = Cart::with('items')->where('user_id', auth()->id())
            ->where('status', 0)
            ->first();

        if (!$cart) {
            return response()->json([
                "items" => [],
                "total" => 0
            ]);
        }

        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->price_at_time;
        });

        return response()->json([
            "cart_id" => $cart->id,
            "items" => $cart->items->map(function ($item) {
                return [
                    "id" => $item->id,
                    "product_id" => $item->product_id,
                    "product_name" => $item->product_name,
                    "product_image" => $item->product_image, 
                    "quantity" => $item->quantity,
                    "price_at_time" => $item->price_at_time,
                ];
            }),
            "total" => $total
        ]);
    }

    /// Thêm vào giỏ hàng
    public function addToCart(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        DB::transaction(function () use ($request, $user) {

            $cart = Cart::firstOrCreate([
                'user_id' => $user->id,
                'status' => 0
            ]);

            $product = Product::findOrFail($request->product_id);

            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'cart_id'       => $cart->id,
                    'product_id'    => $product->id,
                    'quantity'      => 1,
                    'price_at_time' => $product->price,
                    'product_name'  => $product->name,
                    'product_image' => $product->image_url,
                ]);
            }
        });

        return response()->json(["message" => "✅ Đã thêm vào giỏ hàng!"]);
    }



    /// Cập nhật số lượng
    public function updateQuantity(Request $request)
    {
        $item = CartItem::findOrFail($request->item_id);

        $item->update([
            'quantity' => $request->quantity
        ]);

        return response()->json(["message" => "Cập nhật thành công"]);
    }

    /// Xóa 1 item
    public function removeItem($itemId)
    {
        CartItem::findOrFail($itemId)->delete();
        return response()->json(["message" => "Đã xóa sản phẩm khỏi giỏ"]);
    }

    /// Xóa toàn bộ giỏ hàng
    public function clearCart()
    {
        Cart::where("user_id", auth()->id())
            ->where("status", 0)
            ->delete();

        return response()->json(["message" => "Giỏ hàng đã được dọn"]);
    }
}
