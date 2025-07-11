<?php

use App\Http\Controllers\CouponController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EnterpriseController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificationTemplateController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SettingController;
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
    Route::get('/list-select', [EnterpriseController::class, 'indexSelected']);
    Route::get('/{id}', [EnterpriseController::class, 'show']);
    Route::get('/members/{id}', [EnterpriseController::class, 'getMembersByEnterprise']);
    Route::get('/{id}/coupons', [EnterpriseController::class, 'getCouponsInEnterprise']);
    Route::put('/', [EnterpriseController::class, 'update'])->middleware('admin');
    Route::post('/', [EnterpriseController::class, 'store'])->middleware('admin');
    Route::post('/coupon', [EnterpriseController::class, 'setCoupon'])->middleware('admin');
    Route::delete('/{id}', [EnterpriseController::class, 'destroy'])->middleware('admin');
    Route::delete('/{id}/coupon', [EnterpriseController::class, 'destroyCouponByEnterprise'])->middleware('admin');
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
    Route::put('/by-adm', [MemberController::class, 'updateMemberUser'])->middleware('admin');
    Route::delete('/{id}', [MemberController::class, 'destroy'])->middleware('admin');
    Route::delete('/by-adm/{id}', [MemberController::class, 'deleteMemberUser'])->middleware('admin');
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

Route::prefix('feedbacks')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [FeedbackController::class, 'index']);
    Route::get('/saved', [FeedbackController::class, 'getSaved']);
    Route::get('/countFeedbacks', [FeedbackController::class, 'getCountFeedbacks']);
    Route::post('/{id}', [FeedbackController::class, 'store']);
    Route::delete('/{id}', [FeedbackController::class, 'destroy']);
    Route::delete('/saved/{id}', [FeedbackController::class, 'deleteSaved']);
});

Route::prefix('service')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [ServiceController::class, 'index']);
});

Route::prefix('template-notification')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [NotificationTemplateController::class, 'index']);
    Route::post('/', [NotificationTemplateController::class, 'store']);
    Route::put('/', [NotificationTemplateController::class, 'update']);
    Route::delete('/{id}', [NotificationTemplateController::class, 'destroy']);
});

Route::prefix('notification')->middleware(['auth:sanctum'])->group(function () {
    Route::post('/send', [NotificationController::class, 'sendNotification'])->middleware('admin');
});

Route::prefix('setting')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/', [SettingController::class, 'index'])->middleware('admin');
    Route::put('/', [SettingController::class, 'update'])->middleware('admin');
});
