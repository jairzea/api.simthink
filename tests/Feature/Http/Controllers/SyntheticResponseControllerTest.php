<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\SyntheticResponse;
use App\Models\SyntheticUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SyntheticResponseController
 */
final class SyntheticResponseControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $syntheticResponses = SyntheticResponse::factory()->count(3)->create();

        $response = $this->get(route('synthetic-responses.index'));
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SyntheticResponseController::class,
            'store',
            \App\Http\Requests\SyntheticResponseStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $synthetic_user = SyntheticUser::factory()->create();
        $question = fake()->text();
        $answer = fake()->text();

        $response = $this->post(route('synthetic-responses.store'), [
            'synthetic_user_id' => $synthetic_user->id,
            'question' => $question,
            'answer' => $answer,
        ]);

        $syntheticResponses = SyntheticResponse::query()
            ->where('synthetic_user_id', $synthetic_user->id)
            ->where('question', $question)
            ->where('answer', $answer)
            ->get();
        $this->assertCount(1, $syntheticResponses);
        $syntheticResponse = $syntheticResponses->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $syntheticResponse = SyntheticResponse::factory()->create();

        $response = $this->get(route('synthetic-responses.show', $syntheticResponse));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\SyntheticResponseController::class,
            'update',
            \App\Http\Requests\SyntheticResponseUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $syntheticResponse = SyntheticResponse::factory()->create();
        $synthetic_user = SyntheticUser::factory()->create();
        $question = fake()->text();
        $answer = fake()->text();

        $response = $this->put(route('synthetic-responses.update', $syntheticResponse), [
            'synthetic_user_id' => $synthetic_user->id,
            'question' => $question,
            'answer' => $answer,
        ]);

        $syntheticResponse->refresh();

        $response->assertOk();
        $response->assertJsonStructure([]);

        $this->assertEquals($synthetic_user->id, $syntheticResponse->synthetic_user_id);
        $this->assertEquals($question, $syntheticResponse->question);
        $this->assertEquals($answer, $syntheticResponse->answer);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $syntheticResponse = SyntheticResponse::factory()->create();

        $response = $this->delete(route('synthetic-responses.destroy', $syntheticResponse));

        $response->assertNoContent();

        $this->assertModelMissing($syntheticResponse);
    }
}
