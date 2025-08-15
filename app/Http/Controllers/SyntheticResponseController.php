<?php

namespace App\Http\Controllers;

use App\Http\Requests\SyntheticResponseStoreRequest;
use App\Http\Requests\SyntheticResponseUpdateRequest;
use App\Http\Resources\SyntheticResponseResource;
use App\Models\SyntheticResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SyntheticResponseController extends Controller
{
    public function index(Request $request): Response
    {
        $syntheticResponses = SyntheticResponse::all();
    }

    public function store(SyntheticResponseStoreRequest $request): SyntheticResponseResource
    {
        $syntheticResponse = SyntheticResponse::create($request->validated());

        return new SyntheticResponseResource($syntheticResponse);
    }

    public function show(Request $request, SyntheticResponse $syntheticResponse): SyntheticResponseResource
    {
        return new SyntheticResponseResource($syntheticResponse);
    }

    public function update(SyntheticResponseUpdateRequest $request, SyntheticResponse $syntheticResponse): SyntheticResponseResource
    {
        $syntheticResponse->update($request->validated());

        return new SyntheticResponseResource($syntheticResponse);
    }

    public function destroy(Request $request, SyntheticResponse $syntheticResponse): Response
    {
        $syntheticResponse->delete();

        return response()->noContent();
    }
}
