<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\RoleController
 */
final class RoleControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $roles = Role::factory()->count(3)->create();

        $response = $this->get(route('roles.index'));
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RoleController::class,
            'store',
            \App\Http\Requests\RoleStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $name = fake()->name();
        $guard_name = fake()->word();

        $response = $this->post(route('roles.store'), [
            'name' => $name,
            'guard_name' => $guard_name,
        ]);

        $roles = Role::query()
            ->where('name', $name)
            ->where('guard_name', $guard_name)
            ->get();
        $this->assertCount(1, $roles);
        $role = $roles->first();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $role = Role::factory()->create();

        $response = $this->get(route('roles.show', $role));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\RoleController::class,
            'update',
            \App\Http\Requests\RoleUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $role = Role::factory()->create();
        $name = fake()->name();
        $guard_name = fake()->word();

        $response = $this->put(route('roles.update', $role), [
            'name' => $name,
            'guard_name' => $guard_name,
        ]);

        $role->refresh();

        $this->assertEquals($name, $role->name);
        $this->assertEquals($guard_name, $role->guard_name);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $role = Role::factory()->create();

        $response = $this->delete(route('roles.destroy', $role));

        $response->assertNoContent();

        $this->assertModelMissing($role);
    }
}
