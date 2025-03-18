<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['handmade', 'craft', 'art', 'jewelry', 'clothing', 'home decor', 'furniture'];
        $materials = ['wood', 'metal', 'fabric', 'clay', 'glass', 'plastic', 'paper', 'stone', 'leather'];
        $complexity = ['simple', 'moderate', 'complex', 'very complex'];
        $durability = ['low', 'medium', 'high', 'very high'];

        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraphs(3, true),
            'type' => fake()->randomElement($types),
            'material' => fake()->randomElement($materials),
            'production_time' => fake()->numberBetween(1, 30),
            'complexity' => fake()->randomElement($complexity),
            'durability' => fake()->randomElement($durability),
            'unique_features' => fake()->boolean(70) ? fake()->paragraph() : null,
            'contains_external_links' => fake()->boolean(20),
            'maker_id' => User::factory(),
        ];
    }

    /**
     * Indicate that the product is handmade.
     */
    public function handmade(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'handmade',
                'production_time' => fake()->numberBetween(5, 15),
            ];
        });
    }

    /**
     * Indicate that the product is jewelry.
     */
    public function jewelry(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'jewelry',
                'material' => fake()->randomElement(['gold', 'silver', 'copper', 'beads', 'gems']),
                'production_time' => fake()->numberBetween(2, 10),
            ];
        });
    }

    /**
     * Indicate that the product is furniture.
     */
    public function furniture(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'furniture',
                'material' => fake()->randomElement(['wood', 'metal', 'plastic']),
                'production_time' => fake()->numberBetween(10, 30),
                'complexity' => fake()->randomElement(['complex', 'very complex']),
                'durability' => fake()->randomElement(['high', 'very high']),
            ];
        });
    }
}
