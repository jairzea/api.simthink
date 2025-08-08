<?php

namespace App\Http\Controllers;

use App\Enums\InvestigationStatus;
use App\Http\Requests\InvestigationConfirmRequest;
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
        sleep(15);
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

    public function confirm(InvestigationConfirmRequest $request, string $id)
    {
        $investigation = $this->service->findById($id);

        // TODO: Descomentar esta validación cuando se integre  con los agentes
        // if ($investigation->status !== InvestigationStatus::PendingConfirmation) {
        //     return response()->json(['message' => 'Investigación no está pendiente de confirmación.'], 400);
        // }

        // Guardar el nombre de la investigación y pasar a estado confirmado
        $investigation->update([
            'name' => $request->input('name'),
            'status' => InvestigationStatus::Confirmed,
        ]);

        // TODO: Lanzar simulación (puede ser un Job o llamada directa)
        // $this->simulationService->dispatch($investigation);

        // TODO: Actualizar estado de investigación al finalizar 
        $investigation->update([
            'status' => InvestigationStatus::Completed,
        ]);

        return response()->json([
            'message' => 'Investigación confirmada. Procesando...',
            'investigation_id' => $investigation->id,
        ]);
    }
}