<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetails extends Model
{
    use HasFactory;
    protected $fillable = ['specifications', 'manufacturer', 'product_id'];

    public function product()
    {
        return $this->belongsTo(Product::class); // One-to-One
    }
}

