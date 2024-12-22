<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderItem::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $product = Product::factory()->create(); // Create a product for the order item

        return [
            'order_id' => Order::factory(), // Link to a new order
            'product_id' => $product->id, // Link to the product
            'quantity' => $this->faker->numberBetween(1, 5), // Example: Quantity between 1 and 5
            'price' => $product->price, // Use the product's price
        ];
    }
}
