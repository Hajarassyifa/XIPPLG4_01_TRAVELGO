<?php

namespace Database\Factories;

<<<<<<< HEAD
=======
use App\Models\User;
>>>>>>> 7464ce8a8902d7c04506e7e2a27b987706493260
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
<<<<<<< HEAD
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
=======
 * @extends Factory<User>
>>>>>>> 7464ce8a8902d7c04506e7e2a27b987706493260
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
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
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
}
