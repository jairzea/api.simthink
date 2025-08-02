<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'password' => fake()->password(),
            'company' => fake()->company(),
            'phone' => fake()->phoneNumber(),
            'email_verified_at' => fake()->dateTime(),
            'credits' => fake()->numberBetween(-10000, 10000),
            'storage_used_mb' => fake()->numberBetween(-10000, 10000),
            'storage_limit_mb' => fake()->numberBetween(-10000, 10000),
            'remember_token' => fake()->uuid(),
        ];
    }
}
