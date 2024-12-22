<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class); // Many-to-One
    }

    public function details()
    {
        return $this->hasOne(ProductDetails::class); // One-to-One
    }

    public function productDetail()
    {
        return $this->hasOne(ProductDetails::class);
    }

}
