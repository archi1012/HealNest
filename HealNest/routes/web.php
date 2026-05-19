<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MoodTrackingController;
use App\Http\Controllers\ResourcesController;
use Illuminate\Support\Facades\Route;

// Landing
Route::get('/', fn() => view('landing'))->name('home');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Mood
    Route::get('/mood/log', [MoodTrackingController::class, 'create'])->name('mood.create');
    Route::post('/mood/log', [MoodTrackingController::class, 'store'])->name('mood.store');
    Route::get('/mood/history', [MoodTrackingController::class, 'history'])->name('mood.history');

    // Assessment
    Route::get('/assessment/{type}', [AssessmentController::class, 'show'])->name('assessment.show');
    Route::post('/assessment/{type}', [AssessmentController::class, 'store'])->name('assessment.store');
    Route::get('/assessment/result/{id}', [AssessmentController::class, 'result'])->name('assessment.result');

    // Resources
    Route::get('/resources', [ResourcesController::class, 'index'])->name('resources');
});

// Counselor routes
Route::middleware(['auth', 'role:counselor,admin'])->prefix('counselor')->name('counselor.')->group(function () {
    Route::get('/', [CounselorController::class, 'index'])->name('index');
    Route::get('/user/{userId}', [CounselorController::class, 'userDetail'])->name('user');
    Route::post('/user/{userId}/note', [CounselorController::class, 'storeNote'])->name('note');
    Route::post('/alert/{id}/resolve', [CounselorController::class, 'resolveAlert'])->name('resolve');
});

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{id}/role', [AdminController::class, 'updateRole'])->name('users.role');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
});
