<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Ticket API is public because this lab brief specifies a client-side login gate.
    Route::apiResource('tickets', TicketController::class);

    // Product-starter authentication and admin endpoints remain available for reference.
    Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::middleware('product.token')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::apiResource('products', ProductController::class);
        Route::middleware('admin')->prefix('admin')->group(function () {
            Route::get('stats', [AdminController::class, 'stats']);
            Route::get('users', [AdminController::class, 'users']);
            Route::delete('users/{user}', [AdminController::class, 'destroyUser']);
            Route::get('products', [AdminController::class, 'products']);
            Route::delete('products/{product}', [AdminController::class, 'destroyProduct']);
        });
    });
});
