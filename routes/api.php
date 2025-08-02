<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


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