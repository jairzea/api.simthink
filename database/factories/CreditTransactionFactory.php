<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\CreditTransaction;
use App\Models\User;

class CreditTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CreditTransaction::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'amount_usd' => fake()->randomFloat(2, 0, 99999999.99),
            'credits_added' => fake()->numberBetween(-10000, 10000),
            'package_type' => fake()->randomElement(["basic","premium","pro","enterprise"]),
            'payment_method' => fake()->word(),
            'invoice_number' => fake()->word(),
            'status' => fake()->randomElement(["pending","completed","failed"]),
            'metadata' => '{}',
        ];
    }
}
