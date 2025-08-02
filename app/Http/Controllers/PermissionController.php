<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\PermissionUpdateRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends Controller
{
    public function index(Request $request): Response
    {
        $permissions = Permission::all();
    }

    public function store(PermissionStoreRequest $request): Response
    {
        $permission = Permission::create($request->validated());
    }

    public function show(Request $request, Permission $permission): PermissionResource
    {
        return new PermissionResource($permission);
    }

    public function update(PermissionUpdateRequest $request, Permission $permission): Response
    {
        $permission->update($request->validated());
    }

    public function destroy(Request $request, Permission $permission): Response
    {
        $permission->delete();

        return response()->noContent();
    }
}
