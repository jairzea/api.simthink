<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Investigation;
use App\Models\User;

class InvestigationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Investigation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->name(),
            'status' => fake()->randomElement(["created","processing","completed","failed"]),
            'sample_size' => fake()->numberBetween(-10000, 10000),
            'type' => fake()->randomElement(["insight","imss","other"]),
            'use_rag' => fake()->boolean(),
            'cost_credits' => fake()->numberBetween(-10000, 10000),
            'result_summary' => fake()->text(),
            'completed_at' => fake()->dateTime(),
        ];
    }
}
