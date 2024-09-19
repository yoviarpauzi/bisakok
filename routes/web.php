<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::inertia('/login', 'pages/login/User')->name('login');
Route::post('/login', [LoginController::class, 'authUser']);

Route::prefix('admin')->group(function () {
    Route::inertia('/login', 'pages/login/Admin');
    Route::post('/login', [LoginController::class, 'authAdmin']);
});
