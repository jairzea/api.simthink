<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Investigation;
use App\Models\RagUpload;
use App\Models\User;
use App\Models\UserInvestigation;

class RagUploadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = RagUpload::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'investigation_id' => Investigation::factory(),
            'filename' => fake()->word(),
            'size_kb' => fake()->numberBetween(-10000, 10000),
            'file_type' => fake()->randomElement(["pdf","doc","docx","txt","xlsx","image"]),
            'path' => fake()->word(),
            'status' => fake()->randomElement(["uploaded","processed","deleted"]),
            'user_investigation_id' => UserInvestigation::factory(),
        ];
    }
}
