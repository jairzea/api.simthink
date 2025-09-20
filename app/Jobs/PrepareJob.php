<?php

namespace App\Jobs;

use App\Repositories\Contracts\InvestigationRepositoryInterface as Investigations;
use App\Services\AgentGatewayService;
use App\Services\AgentResponseNormalizer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Log;
use Throwable;

class PrepareJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 300;
    public $tries = 6;

    public function backoff(): array
    {
        return [10, 30, 60, 120, 300];
    }

    public function __construct(
        public string $investigationId
    ) {}

    public function handle(Investigations $repo, AgentGatewayService $agents, AgentResponseNormalizer $normalizer): void
    {
        $inv = $repo->find($this->investigationId);
        if (!$inv) return;

        try {
            $response = $agents->prepare([
                'organization_info' => $inv->context_info,
                'research_goal'     => $inv->research_goal,
                'product_info'      => $inv->product_info,
                'persona'           => $inv->target_persona,
                'sample_size'       => $inv->sample_size,
                'user_id'           => 123, // TODO
                'investigation_id'  => $inv->id,
                'use_rag'           => $inv->use_rag,
                'rag_ids'           => [],
            ]);

            // Si consideras que "invalid response" es transitorio -> lanza excepción
            if (!is_array($response) || empty($response['temp_id'])) {
                // Lanzamos excepción para que el job se reintente según $tries y backoff
                throw new \RuntimeException('Prepare returned invalid body');
            }

            $normalized = $normalizer->normalizePrepareResponse($response);

            $repo->markPending($inv, $normalized);

        } catch (Throwable $e) {
            $msg = sprintf("Prepare failed: %s in %s:%d", $e->getMessage(), $e->getFile(), $e->getLine());
            Log::error('PrepareJob exception', ['investigation_id' => $inv->id, 'exception' => $msg, 'trace' => $e->getTraceAsString()]);

            // **No marcar como failed aquí** — relanzamos para que Laravel gestione reintentos
            throw $e;
        }
    }

    /**
     * Este método se ejecuta cuando el job falla definitivamente
     * (después de agotar $tries o cuando worker decide fallar).
     *
     * Nota: no se inyectan dependencias aquí vía tipado, usamos el contenedor.
     */
    public function failed(Throwable $exception): void
    {
        Log::error('PrepareJob finally failed', [
            'investigation_id' => $this->investigationId,
            'exception' => $exception->getMessage(),
        ]);

        // Resolvemos repositorio desde el contenedor para marcar el registro
        try {
            /** @var Investigations $repo */
            $repo = app(Investigations::class);
            $inv = $repo->find($this->investigationId);
            if ($inv) {
                $repo->markFailed($inv, $exception->getMessage());
            }
        } catch (Throwable $repoEx) {
            Log::critical('Failed to mark investigation as failed inside failed() handler', [
                'investigation_id' => $this->investigationId,
                'repo_exception' => $repoEx->getMessage(),
            ]);
        }
    }
}