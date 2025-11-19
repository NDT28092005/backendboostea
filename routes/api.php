<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoogleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\AdminAuthController;
use App\Http\Controllers\Api\GiftController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductReviewController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\HomeApiController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\CheckoutController;
use App\Http\Controllers\NewsletterController;


Route::delete('/cart/clear-cart', [CheckoutController::class, 'clearCart'])
    ->middleware('auth:sanctum');
Route::post('/payment-success', [CheckoutController::class, 'paymentSuccess']);
Route::post('/checkout', [CheckoutController::class, 'checkout']);
Route::get('/orders', [OrderController::class, 'myOrders'])
    ->middleware('auth:sanctum');
Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])
    ->middleware('auth:sanctum');
Route::put('/orders/{orderId}/items/{itemId}/cancel', [OrderController::class, 'cancelOrderItem'])
    ->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', [CheckoutController::class, 'checkout']);
    Route::get('/cart', [CartController::class, 'getCart']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::put('/cart/update', [CartController::class, 'updateQuantity']);
    Route::delete('/cart/remove/{itemId}', [CartController::class, 'removeItem']);
    Route::delete('/cart/clear', [CartController::class, 'clearCart']);
    Route::post('/reviews', [ProductReviewController::class, 'store']);
});
Route::get('/products/{id}/reviews', [ProductReviewController::class, 'listByProduct']);
Route::post('/admin/reviews', [ProductReviewController::class, 'adminCreate']);
Route::prefix('admin/orders')->group(function () {
    Route::get('/', [OrderController::class, 'index']);       // tất cả đơn hàng
    Route::get('/{id}', [OrderController::class, 'show']);    // chi tiết đơn
    Route::put('/{id}/status', [OrderController::class, 'updateStatus']); // đổi trạng thái
});
Route::post('/reviews/{id}/approve', [ProductReviewController::class, 'approve']);
Route::post('/reviews/{id}/reject', [ProductReviewController::class, 'reject']);
Route::delete('/reviews/{id}', [ProductReviewController::class, 'destroy']);
Route::prefix("homepage")->group(function () {
    Route::get("/featured-gifts", [HomeApiController::class, "featuredGifts"]);
    Route::get("/categories", [HomeApiController::class, "categories"]);
    Route::get("/testimonials", [HomeApiController::class, "testimonials"]);
    Route::get('/sliders', [SliderController::class, 'homepageSliders']);
});
Route::prefix('admin')->group(function () {
    Route::apiResource("users", UserController::class);
    Route::apiResource("categories", CategoryController::class);
    Route::apiResource("products", ProductController::class);
    Route::apiResource("gifts", GiftController::class);
    Route::apiResource("testimonials", TestimonialController::class);

    // ✅ Slider RESTful
    Route::apiResource("sliders", SliderController::class);
});
Route::apiResource('orders', OrderController::class)->only(['index', 'store']);

// Admin Routes
Route::post('/admin/login', [AdminAuthController::class, 'login']);
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);
});
Route::middleware('auth:sanctum')->get('/admin/me', [AdminAuthController::class, 'me']);
// -------------------------
// Public Routes
// -------------------------
Route::post('/register', [RegisterController::class, 'register']); // Đăng ký email + gửi mail
Route::post('/login', [AuthenticationController::class, 'login']); // Login email/password
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']); // Đăng ký nhận tin

// Google OAuth
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle']); // Redirect user đến Google
Route::post('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']); // Nhận token từ frontend
Route::post('/email/verify', function (Request $request) {
    $request->validate([
        'id' => 'required|integer|exists:users,id',
        'token' => 'required|string',
    ]);

    $user = User::find($request->id);

    // Kiểm tra token
    if (!Hash::check($user->email, $request->token)) {
        return response()->json(['status' => false, 'message' => 'Token xác nhận không hợp lệ'], 400);
    }

    // Đánh dấu verified
    $user->email_verified_at = now();
    $user->save();

    return response()->json(['status' => true, 'message' => 'Email đã được xác nhận']);
});
Route::middleware('auth:sanctum')->get('/user/me', function (Request $request) {
    return response()->json([
        'user' => $request->user()
    ]);
});
// -------------------------
// Protected Routes (yêu cầu login)
// -------------------------
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [AuthenticationController::class, 'me']);
    Route::get('/logout', [AuthenticationController::class, 'logout']);
    Route::post('/user/update-profile', [UserController::class, 'updateProfile']);
});
