<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestigationStoreRequest;
use App\Http\Requests\InvestigationUpdateRequest;
use App\Http\Resources\InvestigationResource;
use App\Models\Investigation;
use App\Services\InvestigationService;

class InvestigationController extends Controller
{
    public function __construct(
        protected InvestigationService $service,
    ) {}

    public function index()
    {
        $investigations = $this->service->findByUser(request()->input('limit', 10));
    return InvestigationResource::collection($investigations);
    }

    public function store(InvestigationStoreRequest $request)
    {
        $investigation = $this->service->store($request->validated());
        return new InvestigationResource($investigation);
    }

    public function show(Investigation $investigation)
    {
        $investigation->load(['user', 'syntheticUsers', 'ragUpload']);
        return new InvestigationResource($investigation);
    }

    public function update(InvestigationUpdateRequest $request, Investigation $investigation)
    {
        $investigation = $this->service->update($investigation, $request->validated());
        return new InvestigationResource($investigation);
    }

    public function destroy(Investigation $investigation)
    {
        $this->service->delete($investigation);
        return response()->noContent();
    }
}