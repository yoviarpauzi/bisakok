<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentController;
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

        Route::inertia('/classroom', 'pages/Classroom')->name('classroom');
        Route::get('/classroom/index', [ClassroomController::class, 'index']);
        Route::get('/classroom/shows', [ClassroomController::class, 'shows']);
        Route::post('/classroom/store', [ClassroomController::class, 'store']);
        Route::post('/classroom/edit', [ClassroomController::class, 'edit']);
        Route::post('/classroom/delete', [ClassroomController::class, 'delete']);

        Route::inertia('/courses', 'pages/Courses')->name('courses');
        Route::get('/courses/index', [CourseController::class, 'index']);
        Route::get('/courses/shows', [CourseController::class, 'shows']);
        Route::post('/courses/store', [CourseController::class, 'store']);
        Route::post('/courses/edit', [CourseController::class, 'edit']);
        Route::post('/courses/delete', [CourseController::class, 'delete']);

        Route::inertia('/admins', 'pages/Admin')->name('admin');
        Route::get('/admins/shows', [AdminController::class, 'shows']);
        Route::post('/admins/store', [AdminController::class, 'store']);
        Route::post('/admins/edit', [AdminController::class, 'edit']);
        Route::post('/admins/delete', [AdminController::class, 'delete']);

        Route::inertia('/students', 'pages/Student')->name('student');
        Route::get('/students/index', [StudentController::class, 'index']);
        Route::get('/students/shows', [StudentController::class, 'shows']);
        Route::post('/students/store', [StudentController::class, 'store']);
        Route::post('/students/edit', [StudentController::class, 'edit']);
        Route::post('/students/delete', [StudentController::class, 'delete']);
        Route::post('/students/uploadFile', [StudentController::class, 'uploadFile']);
        Route::get('/students/example', [StudentController::class, 'downloadExample']);

        Route::inertia('/tests', 'pages/Test')->name('test');
        Route::get("/tests/shows", [ExamController::class, 'shows']);
        Route::get("/tests/index", [ExamController::class, 'index']);
        Route::post('/tests/store', [ExamController::class, 'store']);
        Route::post('/tests/edit', [ExamController::class, 'edit']);
        Route::post('/tests/delete', [ExamController::class, 'delete']);
        Route::get('/tests/{id}', [ExamController::class, 'show']);

        Route::get('/tests/{id}/questions', [QuestionController::class, 'index']);
        Route::get('/tests/{id}/questions/shows', [QuestionController::class, 'shows']);
        Route::get('/tests/{id}/questions/create', [QuestionController::class, 'create']);
    });
});

Route::middleware(['auth', 'auth.session'])->group(function () {
    Route::get('/users/show', [UserController::class, 'show']);
    Route::get('/users', [UserController::class, 'shows']);
});
