<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateNotificationsRequest;


class UserController extends Controller
{
    public function __construct(
        private UserService $service
    ){}

    public function register(UserStoreRequest $request)
    {
        $user  = $this->service->register($request->validated());
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'user'  => new UserResource($user),
            'token' => $token,
        ], 201);
    }

    public function index()
    {
        return UserResource::collection($this->service->listUsers());
    }

    public function show(User $user)
    {
        return new UserResource($user);
    }

    public function updateProfile(UserUpdateRequest $request)
    {
        // sleep(15);

        $user = $this->service->updateProfile($request->validated());
        return new UserResource($user);
    }

     public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        $this->service->updatePassword($request->validated());
        return response()->json(['message' => 'Contraseña actualizada.']);
    }

    public function updateNotifications(UpdateNotificationsRequest $request): JsonResponse
    {
        $this->service->updateNotifications($request->validated());
        return response()->json(['message' => 'Preferencias actualizadas.']);
    }


    public function destroy(User $user)
    {
        $this->service->deleteUser($user);
        return response()->json(null, 204);
    }
}