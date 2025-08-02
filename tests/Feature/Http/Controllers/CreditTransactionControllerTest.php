<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\CreditTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CreditTransactionController
 */
final class CreditTransactionControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $creditTransactions = CreditTransaction::factory()->count(3)->create();

        $response = $this->get(route('credit-transactions.index'));
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CreditTransactionController::class,
            'store',
            \App\Http\Requests\CreditTransactionStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $amount_usd = fake()->randomFloat(/** decimal_attributes **/);
        $payment_method = fake()->word();
        $user = User::factory()->create();
        $credits_added = fake()->numberBetween(-10000, 10000);
        $package_type = fake()->randomElement(/** enum_attributes **/);
        $invoice_number = fake()->word();
        $status = fake()->randomElement(/** enum_attributes **/);

        $response = $this->post(route('credit-transactions.store'), [
            'amount_usd' => $amount_usd,
            'payment_method' => $payment_method,
            'user_id' => $user->id,
            'credits_added' => $credits_added,
            'package_type' => $package_type,
            'invoice_number' => $invoice_number,
            'status' => $status,
        ]);

        $creditTransactions = CreditTransaction::query()
            ->where('amount_usd', $amount_usd)
            ->where('payment_method', $payment_method)
            ->where('user_id', $user->id)
            ->where('credits_added', $credits_added)
            ->where('package_type', $package_type)
            ->where('invoice_number', $invoice_number)
            ->where('status', $status)
            ->get();
        $this->assertCount(1, $creditTransactions);
        $creditTransaction = $creditTransactions->first();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $creditTransaction = CreditTransaction::factory()->create();

        $response = $this->get(route('credit-transactions.show', $creditTransaction));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CreditTransactionController::class,
            'update',
            \App\Http\Requests\CreditTransactionUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $creditTransaction = CreditTransaction::factory()->create();
        $amount_usd = fake()->randomFloat(/** decimal_attributes **/);
        $payment_method = fake()->word();
        $user = User::factory()->create();
        $credits_added = fake()->numberBetween(-10000, 10000);
        $package_type = fake()->randomElement(/** enum_attributes **/);
        $invoice_number = fake()->word();
        $status = fake()->randomElement(/** enum_attributes **/);

        $response = $this->put(route('credit-transactions.update', $creditTransaction), [
            'amount_usd' => $amount_usd,
            'payment_method' => $payment_method,
            'user_id' => $user->id,
            'credits_added' => $credits_added,
            'package_type' => $package_type,
            'invoice_number' => $invoice_number,
            'status' => $status,
        ]);

        $creditTransaction->refresh();

        $this->assertEquals($amount_usd, $creditTransaction->amount_usd);
        $this->assertEquals($payment_method, $creditTransaction->payment_method);
        $this->assertEquals($user->id, $creditTransaction->user_id);
        $this->assertEquals($credits_added, $creditTransaction->credits_added);
        $this->assertEquals($package_type, $creditTransaction->package_type);
        $this->assertEquals($invoice_number, $creditTransaction->invoice_number);
        $this->assertEquals($status, $creditTransaction->status);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $creditTransaction = CreditTransaction::factory()->create();

        $response = $this->delete(route('credit-transactions.destroy', $creditTransaction));

        $response->assertNoContent();

        $this->assertModelMissing($creditTransaction);
    }
}
