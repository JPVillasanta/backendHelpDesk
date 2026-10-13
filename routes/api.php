<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{AuthController,ProductController};
use App\Http\Controllers\Admin\AdminController;
Route::prefix('v1')->group(function () {
 Route::post('login',[AuthController::class,'login'])->middleware('throttle:10,1');
 Route::middleware('product.token')->group(function () {
  Route::get('me',[AuthController::class,'me']); Route::post('logout',[AuthController::class,'logout']);
  Route::apiResource('products',ProductController::class);
  Route::middleware('admin')->prefix('admin')->group(function () {
   Route::get('stats',[AdminController::class,'stats']); Route::get('users',[AdminController::class,'users']);
   Route::delete('users/{user}',[AdminController::class,'destroyUser']);
   Route::get('products',[AdminController::class,'products']); Route::delete('products/{product}',[AdminController::class,'destroyProduct']);
  });
 });
});