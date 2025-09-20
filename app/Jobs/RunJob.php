<?php
namespace App\Jobs;

use App\Enums\InvestigationStatus;
use App\Models\User;
use App\Repositories\Contracts\InvestigationRepositoryInterface as Investigations;
use App\Services\AgentGatewayService;
use App\Services\UserService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Log;
use Throwable;

class RunJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 900;
    public $tries = 6;

    public function backoff(): array
    {
        return [10, 30, 60, 120, 300];
    }

    public function __construct(
        public string $investigationId,
        public User $user
    ) {}

    public function handle(Investigations $repo, AgentGatewayService $agents, UserService $userService): void
    {
        Log::info('RunJob started', ['investigation_id' => $this->investigationId]);

        $inv = $repo->find($this->investigationId);

        if (! $inv) {
            Log::warning('RunJob: investigation not found', ['investigation_id' => $this->investigationId]);
            return;
        }

        // Log completo (convertir a array para evitar errores)
        $invArr = method_exists($inv, 'toArray') ? $inv->toArray() : (array) $inv;
        Log::info('RunJob loaded investigation', ['investigation' => $invArr]);

        // validation: must be processing
        if ($inv->status !== InvestigationStatus::Processing->value && $inv->status !== 'processing') {
            Log::info('RunJob skipping: invalid status', ['status' => $inv->status, 'investigation_id' => $inv->id]);
            return;
        }

        // validation: temp_id and name must be present
        $tempId = $inv->temp_id ?? null;
        $name = $inv->name ?? $inv->title ?? null;

        if (empty($tempId) || empty($name)) {
            $reason = 'Missing required fields for run: ' . json_encode(['temp_id' => $tempId, 'name' => $name]);
            Log::error('RunJob validation failed', ['investigation_id' => $inv->id, 'reason' => $reason]);

            try {
                $repo->markFailed($inv, $reason);
            } catch (Throwable $repoEx) {
                Log::critical('RunJob: Failed to mark investigation as failed on validation', [
                    'investigation_id' => $inv->id,
                    'repo_exception' => $repoEx->getMessage()
                ]);
            }

            return;
        }

        try {
            
            Log::info('RunJob calling agents->run', [
                'investigation_id' => $inv->id, 
                'temp_id' => $tempId, 
                'name' => $name, 
                'user' => Auth::user()
            ]);

            $result = $agents->run($tempId, $name);

            Log::info('RunJob got response', [
                'investigation_id' => $inv->id, 
                'response' => is_array($result) ? $result : (string)$result
            ]);

            $data = $result['result'];

            $repo->markCompleted($inv, $data);

            $userService->deductUserCredits($this->user, 10, []);

        } catch (Throwable $e) {
            $msg = sprintf("Run failed: %s in %s:%d", $e->getMessage(), $e->getFile(), $e->getLine());
            Log::error('RunJob exception', ['investigation_id' => $inv->id, 'exception' => $msg, 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }

    /**
     * Este método se ejecuta cuando el job falla definitivamente
     * (después de agotar $tries o cuando el worker decide fallar).
     */
    public function failed(Throwable $exception): void
    {
        Log::error('RunJob failed hook', [
            'investigation_id' => $this->investigationId,
            'exception' => $exception->getMessage()
        ]);

        try {
            /** @var Investigations $repo */
            $repo = app()->make(Investigations::class);
            $inv = $repo->find($this->investigationId);
            if ($inv) {
                $repo->markFailed($inv, 'Job permanently failed: '.$exception->getMessage());
            }
        } catch (Throwable $e) {
            Log::critical('RunJob failed hook error', [
                'investigation_id' => $this->investigationId,
                'error' => $e->getMessage()
            ]);
        }
    }
}