<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\CounselorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\MoodTrackingController;
use App\Http\Controllers\ProfileController;
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
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated user routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Mood
    Route::get('/mood/log', [MoodTrackingController::class, 'create'])->name('mood.create');
    Route::post('/mood/log', [MoodTrackingController::class, 'store'])->name('mood.store');
    Route::get('/mood/history', [MoodTrackingController::class, 'history'])->name('mood.history');
    Route::get('/mood/analytics', [MoodTrackingController::class, 'analytics'])->name('mood.analytics');

    // Appointments
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Assessment — result route MUST be before {type} to avoid conflict
    Route::get('/assessment',            [AssessmentController::class, 'index'])->name('assessment.index');
    Route::get('/assessment/result/{id}',[AssessmentController::class, 'result'])->name('assessment.result');
    Route::get('/assessment/{type}',     [AssessmentController::class, 'show'])->name('assessment.show');
    Route::post('/assessment/{type}',    [AssessmentController::class, 'store'])->name('assessment.store');

    // Resources
    Route::get('/resources', [ResourcesController::class, 'index'])->name('resources');

    // Connect to counselor (public for authenticated users)
    Route::get('/connect', [CounselorController::class, 'connect'])->name('connect.counselor');
});

Route::middleware(['auth', 'role:counselor,admin'])->group(function () {
    Route::get('/appointments/manage', [AppointmentController::class, 'manage'])->name('appointments.manage');
    Route::put('/appointments/{id}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.status');
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
    Route::get('/',                          [AdminController::class, 'index'])->name('index');

    // User CRUD
    Route::get('/users',                     [AdminController::class, 'users'])->name('users');
    Route::get('/users/create',              [AdminController::class, 'createUser'])->name('users.create');
    Route::post('/users',                    [AdminController::class, 'storeUser'])->name('users.store');
    Route::get('/users/{id}/edit',           [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{id}',                [AdminController::class, 'updateUser'])->name('users.update');
    Route::delete('/users/{id}',             [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::post('/users/{id}/role',          [AdminController::class, 'updateRole'])->name('users.role');

    // Resource management
    Route::get('/resources',                 [AdminController::class, 'resources'])->name('resources.index');
    Route::get('/resources/create',          [AdminController::class, 'createResource'])->name('resources.create');
    Route::post('/resources',                [AdminController::class, 'storeResource'])->name('resources.store');
    Route::get('/resources/{id}/edit',       [AdminController::class, 'editResource'])->name('resources.edit');
    Route::put('/resources/{id}',            [AdminController::class, 'updateResource'])->name('resources.update');
    Route::delete('/resources/{id}',         [AdminController::class, 'deleteResource'])->name('resources.delete');

    // Counselor management
    Route::get('/counselors',                [AdminController::class, 'counselors'])->name('counselors');
    Route::post('/counselors/{id}/promote',  [AdminController::class, 'promoteToCounselor'])->name('counselors.promote');
    Route::post('/counselors/{id}/demote',   [AdminController::class, 'demoteCounselor'])->name('counselors.demote');

    // Reports
    Route::get('/reports',                   [AdminController::class, 'reports'])->name('reports');
});
