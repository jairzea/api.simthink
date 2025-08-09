<?php

// app/Services/UserService.php
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserService
{
    public function __construct(
        private readonly UserRepository $users
    ) {}

    public function listUsers()
    {
        return $this->users->all();
    }

    public function getUser(int $id)
    {
        return $this->users->find($id);
    }

    public function createUser(array $data): User
    {
        return $this->users->create($data);
    }

    public function updateProfile(array $data): User
    {
        $user = Auth::user();
        return $this->users->update($user, $data);
    }

    public function updatePassword(array $data): void
    {
        
        $this->users->changePassword(
            Auth::user(),
            $data['current_password'],
            $data['password']
        );
    }

    public function deleteUser(User $user): void
    {
        $this->users->delete($user);
    }

    public function register(array $data): User
    {
        return $this->users->create($data);
    }

    public function updateNotifications(array $data): void
    {
        $this->users->updateNotifications(
            Auth::user(),
            (bool) $data['email_notifications'],
            (bool) $data['product_updates']
        );
    }

    public function exportUserData(): void
    {
        $this->users->queueExport(Auth::user());
    }


}