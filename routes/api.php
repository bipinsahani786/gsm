<?php

use App\Http\Controllers\Api\User\AccountController;
use App\Http\Controllers\Api\User\AuthController;
use App\Http\Controllers\Api\User\DashboardController;
use App\Http\Controllers\Api\User\RechargeController;
use App\Http\Controllers\Api\User\SettingController;
use App\Http\Controllers\Api\User\TaskController;
use App\Http\Controllers\Api\User\TeamController;
use App\Http\Controllers\Api\User\WithdrawController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ==========================================
// USER APIs (/api/user/...)
// ==========================================
Route::prefix('user')->group(function () {
    
    // Public Auth Routes
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    // Protected Routes (Require Token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [AuthController::class, 'profile']);
        Route::post('/logout', [AuthController::class, 'logout']);
        
        // ==== DASHBOARD APIs ====
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::post('/level/{id}/join', [DashboardController::class, 'joinLevel']);
        Route::get('/guide', [DashboardController::class, 'guide']);

       // ==== ACCOUNT BINDING APIs ====
        Route::get('/account/bind', [AccountController::class, 'bindIndex']);
        Route::post('/account/bind', [AccountController::class, 'bindStore']);

        // ==== RECHARGE APIs ====
        Route::get('/recharge/config', [RechargeController::class, 'config']);
        Route::post('/recharge', [RechargeController::class, 'store']);
        Route::get('/recharge/history', [RechargeController::class, 'history']);

        // ==== SETTINGS & TRANSACTIONS APIs ====
        Route::post('/settings/change-password', [SettingController::class, 'updatePassword']);
        Route::get('/settings/transactions', [SettingController::class, 'transactions']);

        // ==== TASKS APIs ====
        Route::get('/tasks', [TaskController::class, 'index']);
        Route::post('/tasks/submit', [TaskController::class, 'submit']);

        // ==== TEAM NETWORK API ====
        Route::get('/team', [TeamController::class, 'index']);

        // ==== WITHDRAWAL APIs ====
        Route::get('/withdraw/config', [WithdrawController::class, 'config']);
        Route::post('/withdraw', [WithdrawController::class, 'store']);
        Route::get('/withdraw/history', [WithdrawController::class, 'history']);
    });
});