<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Exceptions\InvalidCredentialsException;

class AuthService
{
    private $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    public function login(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw new InvalidCredentialsException();
        }

        $user  = Auth::user();
        $tokenResult = $user->createToken('api-token');
        $token = $tokenResult->accessToken;

        return ['user' => $user, 'token' => $token];
    }

    public function logout(User $user): void
    {
        $user->token()->revoke();
    }
}