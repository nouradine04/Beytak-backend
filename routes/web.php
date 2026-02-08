<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AuthController;

Route::prefix('api')->group(function () {
    Route::get('/test', function () {
        return response()->json([
            'status' => 'ok',
            'message' => 'API Laravel 12 fonctionne'
        ]);
    });

    // Auth routes
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware(['auth:api'])->group(function () {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/auth/me', [AuthController::class, 'me']);
    });

    // Protected by role
    Route::middleware(['auth:api', 'role:admin'])->group(function () {
        Route::get('/admin/dashboard', function () {
            return response()->json(['message' => 'admin only']);
        });
    });

    Route::middleware(['auth:api', 'role:agence'])->group(function () {
        Route::get('/agence/dashboard', function () {
            return response()->json(['message' => 'agence only']);
        });
    });
});
