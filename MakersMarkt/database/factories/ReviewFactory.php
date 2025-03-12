<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory()->delivered(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->paragraph(),
            'review_date' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the review is positive.
     */
    public function positive(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => fake()->numberBetween(4, 5),
                'comment' => fake()->paragraph() . ' ' . fake()->randomElement([
                    'Excellent product, highly recommended!',
                    'Very satisfied with my purchase.',
                    'Great quality and service!',
                    'I love it! Will buy from this maker again.',
                    'Exceeded my expectations!'
                ]),
            ];
        });
    }

    /**
     * Indicate that the review is negative.
     */
    public function negative(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => fake()->numberBetween(1, 2),
                'comment' => fake()->paragraph() . ' ' . fake()->randomElement([
                    'Disappointed with the quality.',
                    'Not as described in the listing.',
                    'Took too long to arrive.',
                    'Wouldn\'t recommend this product.',
                    'Expected better for the price.'
                ]),
            ];
        });
    }

    /**
     * Indicate that the review is neutral.
     */
    public function neutral(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'rating' => 3,
                'comment' => fake()->paragraph() . ' ' . fake()->randomElement([
                    'It\'s okay, but nothing special.',
                    'Average quality for the price.',
                    'Decent product but delivery was slow.',
                    'Good but there\'s room for improvement.',
                    'Meets expectations but doesn\'t exceed them.'
                ]),
            ];
        });
    }
}