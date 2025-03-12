<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Moderation>
 */
class ModerationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $actions = ['approved', 'rejected', 'flagged', 'warned', 'suspended'];

        return [
            'product_id' => Product::factory(),
            'moderator_id' => User::factory(),
            'reason' => fake()->paragraph(),
            'action_taken' => fake()->randomElement($actions),
            'moderation_date' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }

    /**
     * Indicate that the moderation action is approval.
     */
    public function approved(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'action_taken' => 'approved',
                'reason' => 'Product meets all marketplace guidelines.',
            ];
        });
    }

    /**
     * Indicate that the moderation action is rejection.
     */
    public function rejected(): static
    {
        return $this->state(function (array $attributes) {
            $reasons = [
                'Product contains prohibited content.',
                'Product description is misleading.',
                'Product violates intellectual property rights.',
                'Product does not meet quality standards.',
                'Product contains external links not allowed by policy.'
            ];

            return [
                'action_taken' => 'rejected',
                'reason' => fake()->randomElement($reasons),
            ];
        });
    }

    /**
     * Indicate that the moderation action is flagging.
     */
    public function flagged(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'action_taken' => 'flagged',
                'reason' => 'Product requires additional review before approval.',
            ];
        });
    }
}