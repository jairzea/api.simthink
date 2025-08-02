<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PermissionController
 */
final class PermissionControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_behaves_as_expected(): void
    {
        $permissions = Permission::factory()->count(3)->create();

        $response = $this->get(route('permissions.index'));
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PermissionController::class,
            'store',
            \App\Http\Requests\PermissionStoreRequest::class
        );
    }

    #[Test]
    public function store_saves(): void
    {
        $name = fake()->name();
        $guard_name = fake()->word();

        $response = $this->post(route('permissions.store'), [
            'name' => $name,
            'guard_name' => $guard_name,
        ]);

        $permissions = Permission::query()
            ->where('name', $name)
            ->where('guard_name', $guard_name)
            ->get();
        $this->assertCount(1, $permissions);
        $permission = $permissions->first();
    }


    #[Test]
    public function show_behaves_as_expected(): void
    {
        $permission = Permission::factory()->create();

        $response = $this->get(route('permissions.show', $permission));

        $response->assertOk();
        $response->assertJsonStructure([]);
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PermissionController::class,
            'update',
            \App\Http\Requests\PermissionUpdateRequest::class
        );
    }

    #[Test]
    public function update_behaves_as_expected(): void
    {
        $permission = Permission::factory()->create();
        $name = fake()->name();
        $guard_name = fake()->word();

        $response = $this->put(route('permissions.update', $permission), [
            'name' => $name,
            'guard_name' => $guard_name,
        ]);

        $permission->refresh();

        $this->assertEquals($name, $permission->name);
        $this->assertEquals($guard_name, $permission->guard_name);
    }


    #[Test]
    public function destroy_deletes_and_responds_with(): void
    {
        $permission = Permission::factory()->create();

        $response = $this->delete(route('permissions.destroy', $permission));

        $response->assertNoContent();

        $this->assertModelMissing($permission);
    }
}
