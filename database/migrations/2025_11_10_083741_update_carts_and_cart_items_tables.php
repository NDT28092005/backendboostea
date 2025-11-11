<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update bảng carts
        Schema::table('carts', function (Blueprint $table) {
            $table->unsignedTinyInteger('status')->default(0)->after('user_id'); // 0 = đang tạo, 1 = đã checkout
        });

        // Update bảng cart_items
        Schema::table('cart_items', function (Blueprint $table) {
            $table->decimal('price_at_time', 12, 2)->after('quantity');
            $table->string('product_name')->after('price_at_time');
            $table->string('product_image')->nullable()->after('product_name');
        });
    }

    public function down(): void
    {
        // Xóa cột khi rollback
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn(['price_at_time', 'product_name', 'product_image']);
        });
    }
};
