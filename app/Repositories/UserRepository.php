<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Storage;

class UserRepository implements UserRepositoryInterface
{

    public function findById(string $id): User
    {
        /** @var User */
        return User::query()->findOrFail($id);
    }
    
    public function all(): Collection
    {
        return User::all();
    }

    public function find(int $id): ?User
    {
        return User::find($id);
    }

    public function create(array $data): User
    {
        $data['password'] = Hash::make($data['password']);

        // TODO: Elimnar producción
        $data['credits'] = 40;
        return User::create($data);
    }

     public function update(User $user, array $data): User
    {
        // Proteger email (en la UI es readOnly, aquí lo ignoramos por seguridad)
        unset($data['email']);

        $user->fill([
            'name'    => $data['name'] ?? $user->name,
            'company' => $data['company'] ?? $user->company,
            'phone'   => $data['phone'] ?? $user->phone,
        ])->save();

        return $user->refresh();
    }

    public function updateNotifications(User $user, bool $emailNotifications, bool $productUpdates): User
    {
        $user->fill([
            'email_notifications' => $emailNotifications,
            'product_updates'     => $productUpdates,
        ])->save();

        return $user->refresh();
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (! Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'La contraseña actual no es correcta.',
            ]);
        }

        $user->forceFill([
            'password' => Hash::make($newPassword),
        ])->save();
    }

    public function queueExport(User $user): void
    {
        // Aquí puedes despachar un Job que genere el ZIP y envíe un correo:
        // ExportUserDataJob::dispatch($user->id);
        // Por ahora, placeholder sincrónico opcional:
        // Storage::put("exports/{$user->id}/export.json", json_encode([...]));
    }

    public function deductCreditsAtomic(string $userId, int $credits): bool
    {
        $affected = DB::table('users')
            ->where('id', $userId)
            ->where('credits', '>=', $credits)
            ->update([
                'credits' => DB::raw("credits - {$credits}"),
                'updated_at' => now(),
            ]);

        return $affected === 1;
    }

    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            // 1) Eliminar/limpiar dependencias de dominio (ajusta nombres de relaciones según tus modelos)
            // Ejemplos basados en el modelo de datos inferido:
            // - Investigations (y en cascada SyntheticUsers/SyntheticResponses)
            $user->investigations()
                ->each(function ($investigation) {
                    // Borrar archivos RAG asociados si guardas paths en DB
                    $investigation->ragUploads?->each(function ($rag) {
                        if ($rag->path && Storage::exists($rag->path)) {
                            Storage::delete($rag->path);
                        }
                        $rag->delete();
                    });

                    // Si hay archivos de resultados guardados
                    if ($investigation->result_path && Storage::exists($investigation->result_path)) {
                        Storage::delete($investigation->result_path);
                    }

                    // Borrar entidades hijas si no están en cascade:
                    $investigation->syntheticUsers?->each(function ($su) {
                        $su->syntheticResponses()?->delete();
                        $su->delete();
                    });

                    $investigation->delete();
                });

            // - RAGs subidos por el usuario (no ligados a investigación)
            $user->ragUploads()
                ->whereNull('investigation_id')
                ->each(function ($rag) {
                    if ($rag->path && Storage::exists($rag->path)) {
                        Storage::delete($rag->path);
                    }
                    $rag->delete();
                });

            // - Transacciones de créditos / facturas
            $user->creditTransactions()?->delete();

            // - Carpetas y pivotes
            $user->investigationFolders()?->each(function ($folder) {
                $folder->items()?->delete();
                $folder->delete();
            });

            // 2) Eliminar archivos del usuario en Storage si mantienes un directorio por usuario
            $userDir = "users/{$user->id}";
            if (Storage::exists($userDir)) {
                Storage::deleteDirectory($userDir);
            }

            // 3) Finalmente, eliminar el usuario
            // Si usas SoftDeletes y quieres borrado permanente:
            method_exists($user, 'forceDelete') ? $user->forceDelete() : $user->delete();
        });
    }
}