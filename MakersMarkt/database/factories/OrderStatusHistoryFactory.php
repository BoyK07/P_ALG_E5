<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderStatusHistory>
 */
class OrderStatusHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $oldStatus = fake()->randomElement($statuses);
        $newStatus = fake()->randomElement(array_diff($statuses, [$oldStatus]));

        return [
            'order_id' => Order::factory(),
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_at' => fake()->dateTimeBetween('-30 days', 'now'),
        ];
    }

    /**
     * Create a status change from pending to processing.
     */
    public function pendingToProcessing(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'old_status' => 'pending',
                'new_status' => 'processing',
            ];
        });
    }

    /**
     * Create a status change from processing to shipped.
     */
    public function processingToShipped(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'old_status' => 'processing',
                'new_status' => 'shipped',
            ];
        });
    }

    /**
     * Create a status change from shipped to delivered.
     */
    public function shippedToDelivered(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'old_status' => 'shipped',
                'new_status' => 'delivered',
            ];
        });
    }

    /**
     * Create a status change to cancelled.
     */
    public function toCancelled(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'old_status' => fake()->randomElement(['pending', 'processing']),
                'new_status' => 'cancelled',
            ];
        });
    }
}