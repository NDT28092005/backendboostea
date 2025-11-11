<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gift extends Model
{
    protected $fillable = [
        'name', 'slug', 'image_url', 'price', 'original_price', 'featured', 'category_id'
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
}
