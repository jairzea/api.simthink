<?php

namespace App\Http\Controllers;

use App\Http\Requests\InvestigationStoreRequest;
use App\Http\Requests\InvestigationUpdateRequest;
use App\Http\Resources\InvestigationResource;
use App\Models\Investigation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvestigationController extends Controller
{
    public function index(Request $request): Response
    {
        $investigations = Investigation::all();
    }

    public function store(InvestigationStoreRequest $request): Response
    {
        $investigation = Investigation::create($request->validated());
    }

    public function show(Request $request, Investigation $investigation): InvestigationResource
    {
        return new InvestigationResource($investigation);
    }

    public function update(InvestigationUpdateRequest $request, Investigation $investigation): Response
    {
        $investigation->update($request->validated());
    }

    public function destroy(Request $request, Investigation $investigation): Response
    {
        $investigation->delete();

        return response()->noContent();
    }
}
