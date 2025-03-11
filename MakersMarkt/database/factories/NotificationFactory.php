<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'message' => fake()->sentence(),
            'timestamp' => fake()->dateTimeBetween('-30 days', 'now'),
            'read' => fake()->boolean(30), // 30% chance of being read
        ];
    }

    /**
     * Indicate that the notification is for an order update.
     */
    public function orderUpdate(): static
    {
        return $this->state(function (array $attributes) {
            $status = fake()->randomElement(['processing', 'shipped', 'delivered']);
            return [
                'message' => "Your order has been updated to: $status",
            ];
        });
    }

    /**
     * Indicate that the notification is for a new review.
     */
    public function newReview(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'message' => "Someone left a new review on your product!",
            ];
        });
    }

    /**
     * Indicate that the notification is for a moderation action.
     */
    public function moderationAction(): static
    {
        return $this->state(function (array $attributes) {
            $action = fake()->randomElement(['approved', 'rejected', 'flagged for review']);
            return [
                'message' => "Your product has been $action by a moderator.",
            ];
        });
    }

    /**
     * Indicate that the notification is read.
     */
    public function read(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'read' => true,
            ];
        });
    }

    /**
     * Indicate that the notification is unread.
     */
    public function unread(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'read' => false,
            ];
        });
    }
}