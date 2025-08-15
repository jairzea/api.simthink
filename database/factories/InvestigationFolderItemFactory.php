<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\InvestigationFolder;
use App\Models\Investigation;
use App\Models\InvestigationFolderInvestigation;
use App\Models\InvestigationFolderItem;

class InvestigationFolderItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InvestigationFolderItem::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'folder_id' => InvestigationFolder::factory(),
            'investigation_id' => Investigation::factory(),
        ];
    }
}