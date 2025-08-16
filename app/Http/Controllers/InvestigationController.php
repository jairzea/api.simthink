<?php

namespace App\Http\Controllers;

use App\Enums\InvestigationStatus;
use App\Http\Requests\InvestigationConfirmRequest;
use App\Http\Requests\InvestigationStoreRequest;
use App\Http\Requests\InvestigationUpdateRequest;
use App\Http\Resources\InvestigationResource;
use App\Http\Resources\PrepareStudyResponseResource;
use App\Models\Investigation;
use App\Services\InvestigationService;
use Log;

ini_set('max_execution_time', 2040000);

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
        // sleep(seconds: 15);
        return new PrepareStudyResponseResource($investigation);
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
        if ($investigation->status !== InvestigationStatus::PendingConfirmation->value) {
            return response()->json(['message' => 'Investigación no está pendiente de confirmación.'], 400);
        }

        $data = [
            'name' => $request->input('name'),
            'temp_id' => $request->input('temp_id'),
            'status' => InvestigationStatus::Confirmed,
        ];

        $result = $this->service->confirm($investigation, $data);

        // TODO: Lanzar simulación (puede ser un Job o llamada directa)
        // $this->simulationService->dispatch($investigation);

        $data = $result['result'];
        $agent5 = $data['agent5_market_plan'] ?? '';
        $agent6 = $data['agent6_instruments'] ?? '';
        $refinedHypotheses = $data['refined_hypotheses'] ?? [];
        $hypothesesTexto = collect($refinedHypotheses)
                ->map(fn($item) => '• ' . $item)
                ->implode("\n");

        // TODO: Actualizar estado de investigación al finalizar 
        $investigationResult = $this->service->update($investigation, [
            'status' => InvestigationStatus::Completed,
            'context_info'    => $data['agent5_market_plan'],
            'target_persona'  => $data['refined_customer_profile'],
            'research_goal'   => $hypothesesTexto,
            'product_info'    => $data['product_analysis_summary'],
            'result_summary'  => "🧠 Agente 5:\n{$agent5}\n\n🧪 Agente 6:\n{$agent6}",
        ]);

        Log::info('result', $data);

        return response()->json([
            'message' => 'Investigación confirmada. Procesando...',
            'data' => $investigationResult,
        ]);
    }
}