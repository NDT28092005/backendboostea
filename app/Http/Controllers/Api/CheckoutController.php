<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\CartItem;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(["error" => "Unauthorized"], 401);
        }

        $cart = Cart::with('items')->where("user_id", $user->id)
            ->where("status", 0)
            ->first();

        if (!$cart || $cart->items->count() == 0) {
            return response()->json(["error" => "Giỏ hàng trống"], 400);
        }

        $request->validate([
            "customer_name" => "required",
            "customer_phone" => "required",
            "customer_address" => "required"
        ]);

        DB::beginTransaction();
        try {
            $subtotal = $cart->items->sum(fn($item) => $item->quantity * $item->price_at_time);

            $shipping = $subtotal >= 500000 ? 0 : 30000;

            $order = Order::create([
                "order_code" => "ORDER-" . strtoupper(Str::random(8)),
                "user_id" => $user->id,
                "customer_name" => $request->customer_name,
                "customer_phone" => $request->customer_phone,
                "customer_address" => $request->customer_address,
                "payment_method" => $request->payment_method,
                "total_price" => $subtotal + $shipping,
                "status" => "pending",
                'expires_at' => $request->payment_method === "bank" ? now()->addMinutes(3) : null,
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    "order_id" => $order->id,
                    "product_id" => $item->product_id,
                    "quantity" => $item->quantity,
                    "price" => $item->price_at_time,
                ]);
            }

            DB::commit();

            if ($order->payment_method === "bank") {
                $qrUrl = "https://img.vietqr.io/image/970422-22751921-compact2.png?amount={$order->total_price}&addInfo=" . urlencode("Thanh toán đơn hàng $order->order_code");
            } else {
                $qrUrl = null;
            }

            return response()->json([
                "message" => "Tạo đơn hàng thành công",
                "order_id" => $order->id,
                "order_code" => $order->order_code,
                "amount" => $order->total_price,
                "qr_url" => $qrUrl,
                "payment_method" => $order->payment_method
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }
    public function paymentSuccess(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(["error" => "Unauthorized"], 401);
        }

        // Update order status
        Order::where("id", $request->order_id)
            ->where("status", "pending")
            ->update(["status" => "paid"]);

        return response()->json(["message" => "Cập nhật thanh toán thành công"]);
    }
    public function clearCart()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(["error" => "Unauthorized"], 401);
        }

        $cart = Cart::where("user_id", $user->id)->where("status", 0)->first();

        if (!$cart) {
            return response()->json(["message" => "Giỏ hàng trống rồi"]);
        }

        CartItem::where("cart_id", $cart->id)->delete();
        $cart->delete();

        return response()->json(["message" => "Xóa giỏ hàng thành công"]);
    }
}
