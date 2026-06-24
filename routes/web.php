<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Log;

Route::redirect('/', '/dashboard');

Route::middleware(['auth'])->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // User Management
    Route::resource('users', UserController::class)->except('show');
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/data/ajax', [UserController::class, 'data'])->name('data');
        Route::get('/export', [UserController::class, 'export'])->name('export');
        Route::post('/impersonate', [UserController::class, 'impersonate'])->name('impersonate');
        Route::get('/change-password', [UserController::class, 'passwordChange'])->name('passwordChange');
        Route::post('/change-password', [UserController::class, 'passwordUpdate'])->name('passwordUpdate');
        Route::post('/change-permission/{user}', [UserController::class, 'permissionUpdate'])->name('permissionUpdate');
        Route::post('/status-toggle/{user}', [UserController::class, 'statusToggle'])->name('statusToggle');
        Route::get('/{user}/show', [UserController::class, 'showProfile'])->name('showProfile');
        Route::get('/setting', [UserController::class, 'setting'])->name('setting');
        Route::get('/two-factor', [UserController::class, 'twoFactor'])->name('twoFactor');
    });
});
