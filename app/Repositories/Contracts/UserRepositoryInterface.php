<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Validation\ValidationException;

interface UserRepositoryInterface
{
    public function findById(string $id): User;

    public function update(User $user, array $data): User;

    public function updateNotifications(User $user, bool $emailNotifications, bool $productUpdates): User;

    /**
     * @throws ValidationException si la contraseña actual es incorrecta
     */
    public function changePassword(User $user, string $currentPassword, string $newPassword): void;

    /**
     * Encola la exportación de datos del usuario (ZIP/CSV/PDF) y notifica por email.
     */
    public function queueExport(User $user): void;

    /**
     * Elimina la cuenta y TODAS sus dependencias (investigaciones, RAGs, transacciones, etc.)
     * Usa transacción y borra archivos del Storage.
     */
    public function delete(User $user): void;

    /**
     * Deducción atómica: descuenta créditos sólo si hay saldo suficiente.
     * @return bool true si afectó 1 fila; false si no había saldo suficiente.
     */
    public function deductCreditsAtomic(string $userId, int $credits): bool;
}