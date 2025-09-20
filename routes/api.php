<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InvestigationController;
use App\Http\Controllers\RagUploadController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::prefix('v1')->group(function () {

    // registro e inicio de sesión
     Route::prefix('auth')->group(function () {
        Route::post('/signIn', [UserController::class, 'signIn']);
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
            Route::get('/{id}', [InvestigationController::class, 'show']);
            Route::post('/{id}/confirm', [InvestigationController::class, 'confirm']);
        });

        Route::prefix('rag')->group(function () {
            Route::post('/uploads', [RagUploadController::class, 'store']);
            Route::delete('/uploads/{upload}', [RagUploadController::class, 'destroy']); 
        });

    });
});