<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthRedirectController;
use App\Http\Controllers\StudySessionController;
use App\Http\Controllers\GoogleAuthController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/auth-redirect', [AuthRedirectController::class, 'handle'])->name('auth.redirect');

// Google Auth Routes
Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('google.login');
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Study Room Routes
    Route::get('/study-room', [StudySessionController::class, 'index'])->name('study-room');
    Route::get('/study-room/stats', [StudySessionController::class, 'stats'])->name('study-room.stats');
    Route::post('/study-room/session', [StudySessionController::class, 'store'])->name('study-room.session');
    
    // Fitur Group Study & JaaS Token
    Route::post('/study-room/group', [StudySessionController::class, 'storeGroup'])->name('study.group.store');
    Route::delete('/study-room/group/{group}', [StudySessionController::class, 'destroyGroup'])->name('study.group.destroy');
    
    // --- INI ROUTE BARUNYA ---
    Route::get('/study-room/join/{slug}', [StudySessionController::class, 'joinGroup'])->name('study-room.join');
});

// Forum
Route::get('/forum', [ForumController::class, 'index'])->middleware('auth');

require __DIR__.'/auth.php';