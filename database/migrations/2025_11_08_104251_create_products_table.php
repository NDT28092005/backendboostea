<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();

            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->string('image')->nullable();
            $table->integer('price');                 // giá bán
            $table->integer('stock')->default(0);     // tồn kho
            $table->text('description')->nullable();  // mô tả

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
