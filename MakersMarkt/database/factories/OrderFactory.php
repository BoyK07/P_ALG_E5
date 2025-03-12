<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statusOptions = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];

        return [
            'buyer_id' => User::factory(),
            'product_id' => Product::factory(),
            'store_credit_used' => fake()->randomFloat(2, 0, 100),
            'status' => fake()->randomElement($statusOptions),
            'status_description' => fake()->sentence(),
            'order_date' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the order is pending.
     */
    public function pending(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'status_description' => 'Order has been placed but not yet processed.',
            ];
        });
    }

    /**
     * Indicate that the order is processing.
     */
    public function processing(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'processing',
                'status_description' => 'Order is being prepared by the maker.',
            ];
        });
    }

    /**
     * Indicate that the order is shipped.
     */
    public function shipped(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'shipped',
                'status_description' => 'Order has been shipped and is on its way.',
            ];
        });
    }

    /**
     * Indicate that the order is delivered.
     */
    public function delivered(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'delivered',
                'status_description' => 'Order has been delivered successfully.',
            ];
        });
    }

    /**
     * Indicate that the order is cancelled.
     */
    public function cancelled(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'cancelled',
                'status_description' => 'Order has been cancelled.',
            ];
        });
    }
}