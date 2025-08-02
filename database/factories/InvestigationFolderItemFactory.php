<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Folder;
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
            'folder_id' => Folder::factory(),
            'investigation_id' => Investigation::factory(),
            'investigation_folder_investigation_id' => InvestigationFolderInvestigation::factory(),
        ];
    }
}
