<?php

namespace Database\Factories;

use App\Models\ProductDetails;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductDetailsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductDetails::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'specifications' => $this->faker->paragraph(3), // Example: "Specs: RAM, CPU, Battery life"
            'manufacturer' => $this->faker->company, // Example: "Sony", "Samsung"
            'product_id' => Product::factory(), // Each product detail is linked to a new product
        ];
    }
}
