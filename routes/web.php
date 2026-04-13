<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthRedirectController;
use App\Http\Controllers\StudyRoomController;
use App\Http\Controllers\StudySessionController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
    
Route::get('/auth-redirect', [AuthRedirectController::class, 'handle'])->name('auth.redirect');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/study-room', [StudyRoomController::class, 'index'])->name('study-room');
    Route::get('/study-room/stats', [StudySessionController::class, 'stats'])->name('study-room.stats');
    Route::post('/study-room/session', [StudySessionController::class, 'store'])->name('study-room.session');
});

Route::get('/forum', [ForumController::class, 'index'])
    ->middleware('auth');

require __DIR__.'/auth.php';
