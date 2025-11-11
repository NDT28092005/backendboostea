<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // ✅ USER — Bấm checkout tạo đơn hàng & chuyển sang thanh toán
    public function checkout(Request $request)
    {
        $request->validate([
            'customer_name' => 'required',
            'customer_phone' => 'required',
            'customer_address' => 'required',
            'payment_method' => 'required|in:cod,qr'
        ]);

        $user = auth()->user();

        $cart = Cart::where('user_id', $user->id)->with('items.product')->first();
        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Giỏ hàng trống'], 400);
        }

        DB::beginTransaction();

        try {
            // ✅ tạo order
            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'total_price' => $cart->items->sum(fn($i) => $i->quantity * $i->product->price),
                'status' => $request->payment_method === 'qr'
                    ? 'waiting_payment'
                    : 'pending'
            ]);

            // ✅ lưu các item vào order_items
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ]);
            }

            // ✅ XÓA GIỎ HÀNG
            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            return response()->json([
                'message' => 'Tạo đơn hàng thành công',
                'order_id' => $order->id,
                'status' => $order->status
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // ✅ USER — xem danh sách đơn hàng của mình
    public function myOrders(Request $request)
    {
        $orders = Order::with(['items.product', 'user'])
            ->where("user_id", auth()->id())
            ->orderBy("created_at", "desc")
            ->get();

        return response()->json($orders);
    }

    // ✅ ADMIN — danh sách đơn hàng
    public function index(Request $request)
    {
        $status = $request->status; // filter theo trạng thái

        return Order::with('user')
            ->when($status, fn($q) => $q->where('status', $status))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // ✅ ADMIN — chi tiết 1 đơn hàng
    public function show($id)
    {
        return Order::with(['items.product', 'user'])->findOrFail($id);
    }

    // ✅ ADMIN — thay đổi trạng thái đơn hàng
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            "status" => "required|in:pending,processing,paid,completed,cancelled"
        ]);

        $order->update(["status" => $request->status]);

        return response()->json(["message" => "Cập nhật trạng thái thành công"]);
    }
}
