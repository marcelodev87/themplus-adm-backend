<?php

use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/reset', [UserController::class, 'reset']);
Route::post('/verify', [UserController::class, 'verify']);
Route::post('/new-password', [UserController::class, 'resetPassword']);

Route::prefix('user')->middleware('auth:sanctum')->group(function () {
    Route::put('/password', [UserController::class, 'updatePassword']);
    Route::put('/', [UserController::class, 'update']);
});

Route::prefix('enterprise')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [EnterpriseController::class, 'index']);
    Route::get('/{id}', [EnterpriseController::class, 'show']);
    Route::put('/', [EnterpriseController::class, 'update'])->middleware('admin');
    Route::put('/coupon', [EnterpriseController::class, 'updateCoupon'])->middleware('admin');
    Route::delete('/{id}', [EnterpriseController::class, 'destroy'])->middleware('admin');
});