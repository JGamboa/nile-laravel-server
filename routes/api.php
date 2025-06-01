<?php

use Illuminate\Support\Facades\Route;

use JGamboa\NileLaravelServer\Http\Controllers\Auth\SignupController;
use JGamboa\NileLaravelServer\Http\Controllers\Profile\UserProfileController;
use JGamboa\NileLaravelServer\Http\Controllers\SystemUserController;
use JGamboa\NileLaravelServer\Http\Controllers\TenantController;
use JGamboa\NileLaravelServer\Http\Controllers\TenantInternalUserController;
use JGamboa\NileLaravelServer\Http\Controllers\TenantUserController;
use JGamboa\NileLaravelServer\Http\Controllers\UserTenantController;
use JGamboa\NileLaravelServer\Http\Controllers\Auth\SessionController;
use JGamboa\NileLaravelServer\Http\Controllers\Auth\LoginController;
use JGamboa\NileLaravelServer\Http\Controllers\Auth\LogoutController;
use JGamboa\NileLaravelServer\Http\Middleware\NileContextMiddleware;

Route::prefix('api/nile')->middleware('api')->group(function () {
    Route::put('users/me', [UserProfileController::class, 'update']);

    Route::apiResource('users', TenantInternalUserController::class)->middleware(['auth:sanctum', NileContextMiddleware::class]);

    Route::prefix('admin')->group(function (){
        Route::middleware(NileContextMiddleware::class)->group(function (){
            Route::apiResource('users', TenantInternalUserController::class);
            Route::get('tenants/users/{userId}', [TenantUserController::class, 'show']);
            Route::put('tenants/users/{userId}', [TenantUserController::class, 'update']);
            Route::get('tenants/users', [TenantUserController::class, 'index']);
            Route::post('tenants/users', [TenantUserController::class, 'store']);
            Route::delete('tenants/users', [TenantUserController::class, 'destroy']);
        })->middleware('auth:sanctum');
        Route::apiResource('users', SystemUserController::class)->middleware('auth:sanctum');
    });

    Route::apiResource('tenants', TenantController::class)->middleware('auth:sanctum');

    Route::get('user/tenants', [UserTenantController::class, 'index'])
        ->middleware('auth:sanctum');

    Route::prefix('auth')->group(function () {
        Route::get('session', [SessionController::class, 'show'])->middleware('auth:sanctum');
        Route::post('signin', [LoginController::class, 'login']);
        Route::post('signout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');
    });

    Route::post('signup', [SignupController::class, 'register']);
});


