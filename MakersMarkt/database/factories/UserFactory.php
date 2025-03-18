<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= bcrypt('password'),
            'store_credit' => fake()->randomFloat(2, 0, 1000),
            'profile_bio' => fake()->paragraph(),
            'profile_image' => fake()->imageUrl(),
            'contact_info' => fake()->phoneNumber(),
            'registration_date' => fake()->dateTimeBetween('-2 years', 'now'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is a maker.
     */
    public function maker(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'profile_bio' => fake()->paragraphs(3, true),
            ];
        });
    }

    /**
     * Indicate that the user is a moderator.
     */
    public function moderator(): static
    {
        return $this->state(function (array $attributes) {
            return [];
        });
    }
}
