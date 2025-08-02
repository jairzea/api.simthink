<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Investigation;
use App\Models\SyntheticUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SyntheticUserController
 */
final class SyntheticUserControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $syntheticUsers = SyntheticUser::factory()->count(3)->create();

        $response = $this->get(route('synthetic-users.index'));
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SyntheticUserController::class,
            'store',
            \App\Http\Requests\SyntheticUserStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $investigation = Investigation::factory()->create();
        $code = fake()->word();
        $ocean_profile = fake()->word();

        $response = $this->post(route('synthetic-users.store'), [
            'investigation_id' => $investigation->id,
            'code' => $code,
            'ocean_profile' => $ocean_profile,
        ]);

        $syntheticUsers = SyntheticUser::query()
            ->where('investigation_id', $investigation->id)
            ->where('code', $code)
            ->where('ocean_profile', $ocean_profile)
            ->get();
        $this->assertCount(1, $syntheticUsers);
        $syntheticUser = $syntheticUsers->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $syntheticUser = SyntheticUser::factory()->create();

        $response = $this->get(route('synthetic-users.show', $syntheticUser));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SyntheticUserController::class,
            'update',
            \App\Http\Requests\SyntheticUserUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $syntheticUser = SyntheticUser::factory()->create();
        $investigation = Investigation::factory()->create();
        $code = fake()->word();
        $ocean_profile = fake()->word();

        $response = $this->put(route('synthetic-users.update', $syntheticUser), [
            'investigation_id' => $investigation->id,
            'code' => $code,
            'ocean_profile' => $ocean_profile,
        ]);

        $syntheticUser->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($investigation->id, $syntheticUser->investigation_id);
        $this->assertEquals($code, $syntheticUser->code);
        $this->assertEquals($ocean_profile, $syntheticUser->ocean_profile);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $syntheticUser = SyntheticUser::factory()->create();

        $response = $this->delete(route('synthetic-users.destroy', $syntheticUser));

        $response->assertNoContent();

        $this->assertModelMissing($syntheticUser);
    }
}