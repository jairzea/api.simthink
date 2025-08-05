<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvestigationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::prefix('v1')->group(function () {

    // registro e inicio de sesión
     Route::prefix('auth')->group(function () {
        Route::post('/register', [UserController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
     });
    
    // rutas protegidas
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::apiResource('users', UserController::class)
            ->only(['index', 'show', 'update', 'destroy']);

        Route::prefix('investigations')->group(function () {
            Route::post('/', [InvestigationController::class, 'store']);
            Route::get('/', [InvestigationController::class, 'index']);
        });
    });
});

Route::apiResource('credit-transactions', App\Http\Controllers\CreditTransactionController::class);

Route::apiResource('investigations', App\Http\Controllers\InvestigationController::class);

Route::apiResource('synthetic-users', App\Http\Controllers\SyntheticUserController::class);

Route::apiResource('synthetic-responses', App\Http\Controllers\SyntheticResponseController::class);

Route::apiResource('investigation-folders', App\Http\Controllers\InvestigationFolderController::class);

Route::apiResource('investigation-folder-items', App\Http\Controllers\InvestigationFolderItemController::class);

Route::apiResource('rag-uploads', App\Http\Controllers\RagUploadController::class);

Route::apiResource('users', App\Http\Controllers\UserController::class);

Route::apiResource('roles', App\Http\Controllers\RoleController::class);

Route::apiResource('permissions', App\Http\Controllers\PermissionController::class);