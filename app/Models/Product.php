<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'image',
        'image_url',
        'price',
        'original_price',
        'stock',
        'featured',
        'description'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'price' => 'integer',
        'original_price' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    public function descriptions()
    {
        return $this->hasMany(ProductDescription::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true);
    }
}
