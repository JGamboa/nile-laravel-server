<?php

use Illuminate\Support\Facades\Route;

use JGamboa\NileLaravelServer\Http\Controllers\Auth\SignupController;
use JGamboa\NileLaravelServer\Http\Controllers\Profile\UserProfileController;
use JGamboa\NileLaravelServer\Http\Controllers\TenantController;
use JGamboa\NileLaravelServer\Http\Controllers\TenantUserController;
use JGamboa\NileLaravelServer\Http\Controllers\UserTenantController;
use JGamboa\NileLaravelServer\Http\Controllers\Auth\SessionController;
use JGamboa\NileLaravelServer\Http\Controllers\Auth\LoginController;
use JGamboa\NileLaravelServer\Http\Controllers\Auth\LogoutController;
use JGamboa\NileLaravelServer\Http\Middleware\NileContextMiddleware;

Route::prefix('api/nile')->middleware('api')->group(function () {
    Route::put('users/me', [UserProfileController::class, 'update']);
    Route::post('tenants/users', [TenantUserController::class, 'store']);
    Route::delete('tenants/users', [TenantUserController::class, 'destroy']);
    Route::get('tenants/users', [TenantUserController::class, 'index'])
        ->middleware(['auth:sanctum', NileContextMiddleware::class]);

    Route::post('tenants', [TenantController::class, 'create'])->middleware('auth:sanctum');
    Route::get('tenants', [TenantController::class, 'index'])->middleware('auth:sanctum');


    Route::get('user/tenants', [UserTenantController::class, 'list'])
        ->middleware('auth:sanctum');

    Route::prefix('auth')->group(function () {
        Route::get('session', [SessionController::class, 'show'])->middleware('auth:sanctum');
        Route::post('signin', [LoginController::class, 'login']);
        Route::post('signout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');
    });

    Route::post('signup', [SignupController::class, 'register']);
});


