<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Investigation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\InvestigationController
 */
final class InvestigationControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $investigations = Investigation::factory()->count(3)->create();

        $response = $this->get(route('investigations.index'));
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\InvestigationController::class,
            'store',
            \App\Http\Requests\InvestigationStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $name = fake()->name();
        $sample_size = fake()->numberBetween(-10000, 10000);
        $type = fake()->randomElement(/** enum_attributes **/);
        $use_rag = fake()->boolean();
        $user = User::factory()->create();
        $status = fake()->randomElement(/** enum_attributes **/);
        $cost_credits = fake()->numberBetween(-10000, 10000);

        $response = $this->post(route('investigations.store'), [
            'name' => $name,
            'sample_size' => $sample_size,
            'type' => $type,
            'use_rag' => $use_rag,
            'user_id' => $user->id,
            'status' => $status,
            'cost_credits' => $cost_credits,
        ]);

        $investigations = Investigation::query()
            ->where('name', $name)
            ->where('sample_size', $sample_size)
            ->where('type', $type)
            ->where('use_rag', $use_rag)
            ->where('user_id', $user->id)
            ->where('status', $status)
            ->where('cost_credits', $cost_credits)
            ->get();
        $this->assertCount(1, $investigations);
        $investigation = $investigations->first();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $investigation = Investigation::factory()->create();

        $response = $this->get(route('investigations.show', $investigation));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\InvestigationController::class,
            'update',
            \App\Http\Requests\InvestigationUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $investigation = Investigation::factory()->create();
        $name = fake()->name();
        $sample_size = fake()->numberBetween(-10000, 10000);
        $type = fake()->randomElement(/** enum_attributes **/);
        $use_rag = fake()->boolean();

        $response = $this->put(route('investigations.update', $investigation), [
            'name' => $name,
            'sample_size' => $sample_size,
            'type' => $type,
            'use_rag' => $use_rag,
        ]);

        $investigation->refresh();

        $this->assertEquals($name, $investigation->name);
        $this->assertEquals($sample_size, $investigation->sample_size);
        $this->assertEquals($type, $investigation->type);
        $this->assertEquals($use_rag, $investigation->use_rag);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $investigation = Investigation::factory()->create();

        $response = $this->delete(route('investigations.destroy', $investigation));

        $response->assertNoContent();

        $this->assertModelMissing($investigation);
    }
}
