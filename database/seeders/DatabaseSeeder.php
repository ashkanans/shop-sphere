<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetails;
use App\Models\Order;
use App\Models\OrderItem;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create categories with products and product details
        Category::factory(5)->has(
            Product::factory(10)->has(ProductDetails::factory())
        )->create();

        // Create orders with items
        Order::factory(10)->has(OrderItem::factory(3))->create();
    }
}

