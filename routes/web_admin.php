<?php

use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\PasswordController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::prefix('admin')
    ->name('admin')
    ->group(function () {

        Route::middleware('guest:web')->group(function () {

            Route::get('/login', [LoginController::class, 'create'])
                ->name('.login');

            Route::post('login', [LoginController::class, 'store']);

            Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
                ->name('.password.request');

            Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
                ->name('.password.email');

            Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
                ->name('.password.reset');

            Route::post('/reset-password', [NewPasswordController::class, 'store'])
                ->name('.password.store');

        });

        Route::middleware('auth:web')->group(function () {
            Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
                ->name('.password.confirm');

            Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

            Route::put('/password', [PasswordController::class, 'update'])->name('.password.update');


            Route::get('/dashboard', [DashboardController::class, 'index'])->name('.dashboard');
        });

        Route::controller(ProfileController::class)->group(function () {
            Route::get('/profile', 'edit')->name('.profile.edit');
            Route::patch('/profile', 'update')->name('.profile.update');
            Route::delete('/profile', 'destroy')->name('.profile.destroy');
        });


        Route::post('/logout', [LoginController::class, 'destroy'])
            ->name('.logout');
    });
