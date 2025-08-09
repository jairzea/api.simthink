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

        Route::prefix('me')->group(function () {
            Route::patch('/updatePassword', [UserController::class, 'updatePassword']);
            Route::patch('/updateNotifications', [UserController::class, 'updateNotifications']);
            Route::get('/{user}', [UserController::class, 'show']);
            Route::put('/', [UserController::class, 'updateProfile']);
            Route::delete('/', [UserController::class, 'destroy']);

        });


        Route::prefix('investigations')->group(function () {
            Route::post('/', [InvestigationController::class, 'store']);
            Route::get('/', [InvestigationController::class, 'index']);
            Route::post('/{id}/confirm', [InvestigationController::class, 'confirm']);
        });

    });
});