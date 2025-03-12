<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->word(),
        ];
    }

    /**
     * Indicate that this is an admin role.
     */
    public function admin(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'admin',
            ];
        });
    }

    /**
     * Indicate that this is a moderator role.
     */
    public function moderator(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'moderator',
            ];
        });
    }

    /**
     * Indicate that this is a maker role.
     */
    public function maker(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'maker',
            ];
        });
    }

    /**
     * Indicate that this is a buyer role.
     */
    public function buyer(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'buyer',
            ];
        });
    }
}