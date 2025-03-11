<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $reasons = [
            'Counterfeit or unauthorized product',
            'Misleading description',
            'Inappropriate content',
            'Copyright violation',
            'Prohibited item',
            'Spam or scam',
            'Other violation of terms'
        ];

        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'reason' => fake()->randomElement($reasons),
            'report_date' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }

    /**
     * Indicate that the report is for a copyright violation.
     */
    public function copyrightViolation(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'reason' => 'This product infringes on a copyright or trademark I own.',
            ];
        });
    }

    /**
     * Indicate that the report is for misleading information.
     */
    public function misleadingInfo(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'reason' => 'The product description is misleading or contains false information.',
            ];
        });
    }

    /**
     * Indicate that the report is for prohibited items.
     */
    public function prohibitedItem(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'reason' => 'This item is prohibited from being sold on the marketplace.',
            ];
        });
    }
}