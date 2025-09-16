<?php

namespace App\Http\Controllers;

use App\Http\Requests\SyntheticUserStoreRequest;
use App\Http\Requests\SyntheticUserUpdateRequest;
use App\Http\Resources\SyntheticUserResource;
use App\Models\SyntheticUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SyntheticUserController extends Controller
{
    public function index(Request $request): Response
    {
        $syntheticUsers = SyntheticUser::all();
    }

    public function store(SyntheticUserStoreRequest $request): SyntheticUserResource
    {
        $syntheticUser = SyntheticUser::create($request->validated());

        return new SyntheticUserResource($syntheticUser);
    }

    public function show(Request $request, SyntheticUser $syntheticUser): SyntheticUserResource
    {
        return new SyntheticUserResource($syntheticUser);
    }

    public function update(SyntheticUserUpdateRequest $request, SyntheticUser $syntheticUser): SyntheticUserResource
    {
        $syntheticUser->update($request->validated());

        return new SyntheticUserResource($syntheticUser);
    }

    public function destroy(Request $request, SyntheticUser $syntheticUser): Response
    {
        $syntheticUser->delete();

        return response()->noContent();
    }
}