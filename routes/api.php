<?php

use App\Http\Controllers\CouponController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\SubscriptionController;
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

Route::prefix('coupon')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [CouponController::class, 'index']);
    Route::get('/{id}', [CouponController::class, 'show']);
    Route::post('/', [CouponController::class, 'store']);
    Route::put('/', [CouponController::class, 'update'])->middleware('admin');
    Route::delete('/{id}', [CouponController::class, 'destroy'])->middleware('admin');
});

Route::prefix('member')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [MemberController::class, 'index']);
    Route::get('/find/{id}', [MemberController::class, 'show']);
    Route::post('/', [MemberController::class, 'store'])->middleware('admin');
    Route::put('/active', [MemberController::class, 'active'])->middleware('admin');
    Route::put('/', [MemberController::class, 'update'])->middleware('admin');
    Route::delete('/{id}', [MemberController::class, 'destroy'])->middleware('admin');
});

Route::prefix('department')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [DepartmentController::class, 'index']);
    Route::post('/', [DepartmentController::class, 'store'])->middleware('admin');
    Route::put('/', [DepartmentController::class, 'update'])->middleware('admin');
    Route::delete('/{id}', [DepartmentController::class, 'destroy'])->middleware('admin');
});

Route::prefix('subscription')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [SubscriptionController::class, 'index']);
    Route::put('/', [SubscriptionController::class, 'update'])->middleware('admin');
});
