<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function __construct(private AuthService $service) {}


    public function login(LoginRequest $request)
    {
        $result = $this->service->login($request->validated());

        return response()->json([
            'user'         => new UserResource($result['user']),
            'access_token' => $result['token'],
            'token_type'   => 'Bearer',
        ]);
    }

    public function logout(Request $request)
    {
        $this->service->logout($request->user());
        return response()->json([], 204);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}