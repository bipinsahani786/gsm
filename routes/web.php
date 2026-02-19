<?php

use App\Http\Controllers\Backend\Admins\Auth\AdminAuthController as AdminAuth;

use App\Http\Controllers\Backend\Admins\Dashboard\AdminConfigurationController;
use App\Http\Controllers\Backend\Admins\Dashboard\AdminDepositController;
use App\Http\Controllers\Backend\Admins\Dashboard\AdminLevelController;
use App\Http\Controllers\Backend\Admins\Dashboard\AdminPositionController;
use App\Http\Controllers\Backend\Admins\Dashboard\AdminWithdrawalController;
use App\Http\Controllers\Backend\Users\Auth\UserAuthController as UserAuth;
use App\Http\Controllers\Backend\Users\Dashboard\UserAccountController;
use App\Http\Controllers\Backend\Users\Dashboard\UserDashboardController;
use App\Http\Controllers\Backend\Users\Dashboard\UserRechargeController;
use App\Http\Controllers\Backend\Users\Dashboard\UserSettingController;
use App\Http\Controllers\Backend\Users\Dashboard\UserWithdrawController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// --------------------------------------------------------------------------
// USER ROUTES (Guard: web)
// --------------------------------------------------------------------------
Route::middleware('guest:web')->group(function () {
    Route::get('/login', [UserAuth::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserAuth::class, 'login'])->name('login.post');
    Route::get('/register', [UserAuth::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserAuth::class, 'register'])->name('register.post');
});

Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');

    // User Dashboard & Levels
    Route::post('/level/{id}/join', [UserDashboardController::class, 'joinLevel'])->name('user.level.join');
    Route::get('/funds', function () {
        return view('backend.users.pages.funds');
    })->name('user.funds');

    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('user.profile');

    //recharge
    Route::get('/recharge', [UserRechargeController::class, 'index'])->name('user.recharge');
    Route::post('/recharge', [UserRechargeController::class, 'store'])->name('user.recharge.store');
    Route::get('/recharge/history', [UserRechargeController::class, 'history'])->name('user.recharge.history');
    //add account
    Route::get('/bind-account', [UserAccountController::class, 'bindIndex'])->name('user.bind.index');
    Route::post('/bind-account', [UserAccountController::class, 'bindStore'])->name('user.bind.save');

    //withdrawal
    Route::get('/withdraw', [UserWithdrawController::class, 'index'])->name('user.withdraw');
    Route::post('/withdraw', [UserWithdrawController::class, 'store'])->name('user.withdraw.store');
    Route::get('/withdraw/history', [UserWithdrawController::class, 'history'])->name('user.withdraw.history');

    //transactions
    Route::get('/transactions', [UserSettingController::class, 'transactions'])->name('user.transactions');


    // User Settings Routes
    Route::get('/settings/password', [UserSettingController::class, 'changePasswordIndex'])->name('user.password.index');
    Route::post('/settings/password', [UserSettingController::class, 'updatePassword'])->name('user.password.update');

    Route::post('/logout', [UserAuth::class, 'logout'])->name('logout');

});


// --------------------------------------------------------------------------
// ADMIN ROUTES (Prefix: admin, Guard: admin)
// --------------------------------------------------------------------------
Route::prefix('admin')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AdminAuth::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminAuth::class, 'login'])->name('admin.login.post');
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', function () {
            return view('backend.admins.pages.dashboard');
        })->name('admin.dashboard');

        Route::post('/logout', [AdminAuth::class, 'logout'])->name('admin.logout');


        Route::get('/settings', [AdminConfigurationController::class, 'index'])->name('admin.settings');
        Route::post('/settings', [AdminConfigurationController::class, 'update'])->name('admin.config.update');
        Route::get('/settings/withdrawals', [AdminConfigurationController::class, 'withdrawalSettings'])
            ->name('admin.settings.withdrawal');

        Route::post('/settings/withdrawals', [AdminConfigurationController::class, 'updateWithdrawalSettings'])
            ->name('admin.settings.withdrawal.update');

        Route::get('/levels', [AdminLevelController::class, 'index'])->name('admin.levels.index');
        Route::post('/levels', [AdminLevelController::class, 'store'])->name('admin.levels.store');
        Route::delete('/levels/{id}', [AdminLevelController::class, 'destroy'])->name('admin.levels.destroy');


        Route::get('/positions', [AdminPositionController::class, 'index'])->name('admin.positions.index');
        Route::post('/positions', [AdminPositionController::class, 'store'])->name('admin.positions.store');
        Route::delete('/positions/{id}', [AdminPositionController::class, 'destroy'])->name('admin.positions.destroy');
        Route::put('/positions/{id}/status', [AdminPositionController::class, 'toggleStatus'])->name('admin.positions.status');

        Route::get('/deposits', [AdminDepositController::class, 'index'])->name('admin.deposits.index');
        Route::post('/deposits/{id}/approve', [AdminDepositController::class, 'approve'])->name('admin.deposits.approve');
        Route::post('/deposits/{id}/reject', [AdminDepositController::class, 'reject'])->name('admin.deposits.reject');

        //withdrawal
        Route::get('/withdrawals', [AdminWithdrawalController::class, 'index'])->name('admin.withdrawals.index');
        Route::post('/withdrawals/{id}/approve', [AdminWithdrawalController::class, 'approve'])->name('admin.withdrawals.approve');
        Route::post('/withdrawals/{id}/reject', [AdminWithdrawalController::class, 'reject'])->name('admin.withdrawals.reject');

        //

    });

});