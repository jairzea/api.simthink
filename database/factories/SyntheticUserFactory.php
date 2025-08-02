<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Investigation;
use App\Models\SyntheticUser;

class SyntheticUserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SyntheticUser::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'investigation_id' => Investigation::factory(),
            'code' => fake()->unique()->lexify('user_????'),
            'ocean_profile' => '{}',
            'metadata' => '{}',
        ];
    }
}