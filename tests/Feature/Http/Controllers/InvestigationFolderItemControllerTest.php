<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\InvestigationFolder;
use App\Models\Investigation;
use App\Models\InvestigationFolderInvestigation;
use App\Models\InvestigationFolderItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\InvestigationFolderItemController
 */
final class InvestigationFolderItemControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $investigationFolderItems = InvestigationFolderItem::factory()->count(3)->create();

        $response = $this->get(route('investigation-folder-items.index'));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\InvestigationFolderItemController::class,
            'store',
            \App\Http\Requests\InvestigationFolderItemStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $folder = InvestigationFolder::factory()->create();
        $investigation = Investigation::factory()->create();

        $response = $this->post(route('investigation-folder-items.store'), [
            'folder_id' => $folder->id,
            'investigation_id' => $investigation->id,
        ]);

        $investigationFolderItems = InvestigationFolderItem::query()
            ->where('folder_id', $folder->id)
            ->where('investigation_id', $investigation->id)
            ->get();
        $this->assertCount(1, $investigationFolderItems);
        $investigationFolderItem = $investigationFolderItems->first();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $investigationFolderItem = InvestigationFolderItem::factory()->create();

        $response = $this->get(route('investigation-folder-items.show', $investigationFolderItem));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\InvestigationFolderItemController::class,
            'update',
            \App\Http\Requests\InvestigationFolderItemUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $investigationFolderItem = InvestigationFolderItem::factory()->create();
        $folder = InvestigationFolder::factory()->create();
        $investigation = Investigation::factory()->create();
        $investigation_folder_investigation = InvestigationFolderInvestigation::factory()->create();

        $response = $this->put(route('investigation-folder-items.update', $investigationFolderItem), [
            'folder_id' => $folder->id,
            'investigation_id' => $investigation->id,
        ]);

        $investigationFolderItem->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($folder->id, $investigationFolderItem->folder_id);
        $this->assertEquals($investigation->id, $investigationFolderItem->investigation_id);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $investigationFolderItem = InvestigationFolderItem::factory()->create();

        $response = $this->delete(route('investigation-folder-items.destroy', $investigationFolderItem));

        $response->assertNoContent();

        $this->assertModelMissing($investigationFolderItem);
    }
}