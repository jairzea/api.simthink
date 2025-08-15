<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\UserController
 */
final class UserControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $users = User::factory()->count(3)->create();

        $response = $this->get(route('users.index'));
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserController::class,
            'store',
            \App\Http\Requests\UserStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $name = fake()->name();
        $email = fake()->safeEmail();
        $password = fake()->password();
        $credits = fake()->numberBetween(-10000, 10000);
        $storage_used_mb = fake()->numberBetween(-10000, 10000);
        $storage_limit_mb = fake()->numberBetween(-10000, 10000);

        $response = $this->post(route('users.store'), [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'credits' => $credits,
            'storage_used_mb' => $storage_used_mb,
            'storage_limit_mb' => $storage_limit_mb,
        ]);

        $users = User::query()
            ->where('name', $name)
            ->where('email', $email)
            ->where('password', $password)
            ->where('credits', $credits)
            ->where('storage_used_mb', $storage_used_mb)
            ->where('storage_limit_mb', $storage_limit_mb)
            ->get();
        $this->assertCount(1, $users);
        $user = $users->first();

        $response->assertCreated();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.show', $user));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\UserController::class,
            'update',
            \App\Http\Requests\UserUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $user = User::factory()->create();
        $name = fake()->name();
        $email = fake()->safeEmail();
        $company = fake()->company();
        $phone = fake()->phoneNumber();
        $storage_limit_mb = fake()->numberBetween(-10000, 10000);

        $response = $this->put(route('users.update', $user), [
            'name' => $name,
            'email' => $email,
            'company' => $company,
            'phone' => $phone,
            'storage_limit_mb' => $storage_limit_mb,
        ]);

        $user->refresh();

        $this->assertEquals($name, $user->name);
        $this->assertEquals($email, $user->email);
        $this->assertEquals($company, $user->company);
        $this->assertEquals($phone, $user->phone);
        $this->assertEquals($storage_limit_mb, $user->storage_limit_mb);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $user = User::factory()->create();

        $response = $this->delete(route('users.destroy', $user));

        $response->assertNoContent();

        $this->assertModelMissing($user);
    }
}
