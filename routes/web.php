<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::inertia('/login', 'pages/login/User')->name('login');
Route::post('/login', [LoginController::class, 'authUser']);

Route::middleware(['user', 'auth.session'])->group(function () {
    Route::redirect('/', '/dashboard');
    Route::inertia('/dashboard', 'pages/dashboard/User');

    Route::get('/signout', [LogoutController::class, 'userLogout']);
});

Route::prefix('admin')->group(function () {
    Route::inertia('/login', 'pages/login/Admin');
    Route::post('/login', [LoginController::class, 'authAdmin']);

    Route::middleware(['admin', 'auth.session'])->group(function () {
        Route::redirect('/', '/admin/dashboard');
        Route::inertia('/dashboard', 'pages/dashboard/Admin');

        Route::get('/signout', [LogoutController::class, 'adminLogout']);
    });
});


Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/users/show', [UserController::class, 'show']);
    Route::get('/users', [UserController::class, 'shows']);
});
