<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\RagUpload;
use App\Models\User;
use App\Models\UserInvestigation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\RagUploadController
 */
final class RagUploadControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $ragUploads = RagUpload::factory()->count(3)->create();

        $response = $this->get(route('rag-uploads.index'));
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RagUploadController::class,
            'store',
            \App\Http\Requests\RagUploadStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $user = User::factory()->create();
        $filename = fake()->word();
        $size_kb = fake()->numberBetween(-10000, 10000);
        $file_type = fake()->randomElement(/** enum_attributes **/);
        $path = fake()->word();
        $status = fake()->randomElement(/** enum_attributes **/);
        $user_investigation = UserInvestigation::factory()->create();

        $response = $this->post(route('rag-uploads.store'), [
            'user_id' => $user->id,
            'filename' => $filename,
            'size_kb' => $size_kb,
            'file_type' => $file_type,
            'path' => $path,
            'status' => $status,
        ]);

        $ragUploads = RagUpload::query()
            ->where('user_id', $user->id)
            ->where('filename', $filename)
            ->where('size_kb', $size_kb)
            ->where('file_type', $file_type)
            ->where('path', $path)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $ragUploads);
        $ragUpload = $ragUploads->first();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $ragUpload = RagUpload::factory()->create();

        $response = $this->get(route('rag-uploads.show', $ragUpload));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RagUploadController::class,
            'update',
            \App\Http\Requests\RagUploadUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $ragUpload = RagUpload::factory()->create();
        $user = User::factory()->create();
        $filename = fake()->word();
        $size_kb = fake()->numberBetween(-10000, 10000);
        $file_type = fake()->randomElement(/** enum_attributes **/);
        $path = fake()->word();
        $status = fake()->randomElement(/** enum_attributes **/);
        $user_investigation = UserInvestigation::factory()->create();

        $response = $this->put(route('rag-uploads.update', $ragUpload), [
            'user_id' => $user->id,
            'filename' => $filename,
            'size_kb' => $size_kb,
            'file_type' => $file_type,
            'path' => $path,
            'status' => $status,
        ]);

        $ragUpload->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($user->id, $ragUpload->user_id);
        $this->assertEquals($filename, $ragUpload->filename);
        $this->assertEquals($size_kb, $ragUpload->size_kb);
        $this->assertEquals($file_type, $ragUpload->file_type);
        $this->assertEquals($path, $ragUpload->path);
        $this->assertEquals($status, $ragUpload->status);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $ragUpload = RagUpload::factory()->create();

        $response = $this->delete(route('rag-uploads.destroy', $ragUpload));

        $response->assertNoContent();

        $this->assertModelMissing($ragUpload);
    }
}