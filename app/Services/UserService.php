<?php

// app/Services/UserService.php
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

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

    public function updateUser(User $user, array $data): User
    {
        return $this->users->update($user, $data);
    }

    public function deleteUser(User $user): void
    {
        $this->users->delete($user);
    }

    public function register(array $data): User
    {
        return $this->users->create($data);
    }

}