<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\InvestigationFolder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\InvestigationFolderController
 */
final class InvestigationFolderControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $investigationFolders = InvestigationFolder::factory()->count(3)->create();

        $response = $this->get(route('investigation-folders.index'));
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\InvestigationFolderController::class,
            'store',
            \App\Http\Requests\InvestigationFolderStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $name = fake()->name();
        $user = User::factory()->create();

        $response = $this->post(route('investigation-folders.store'), [
            'name' => $name,
            'user_id' => $user->id,
        ]);

        $investigationFolders = InvestigationFolder::query()
            ->where('name', $name)
            ->where('user_id', $user->id)
            ->get();
        $this->assertCount(1, $investigationFolders);
        $investigationFolder = $investigationFolders->first();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $investigationFolder = InvestigationFolder::factory()->create();

        $response = $this->get(route('investigation-folders.show', $investigationFolder));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\InvestigationFolderController::class,
            'update',
            \App\Http\Requests\InvestigationFolderUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $investigationFolder = InvestigationFolder::factory()->create();
        $user = User::factory()->create();
        $name = fake()->name();

        $response = $this->put(route('investigation-folders.update', $investigationFolder), [
            'user_id' => $user->id,
            'name' => $name,
        ]);

        $investigationFolder->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user->id, $investigationFolder->user_id);
        $this->assertEquals($name, $investigationFolder->name);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $investigationFolder = InvestigationFolder::factory()->create();

        $response = $this->delete(route('investigation-folders.destroy', $investigationFolder));

        $response->assertNoContent();

        $this->assertModelMissing($investigationFolder);
    }
}
