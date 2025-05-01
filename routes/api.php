<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('/login', LoginController::class);
Route::apiResource('/register', RegisterController::class);
Route::apiResource('/category', CategoryController::class);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/category-list', [CategoryController::class, 'getCategoryList']);
    Route::apiResource('/categories', CategoryController::class);
});
