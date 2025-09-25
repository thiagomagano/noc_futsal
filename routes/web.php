<?php

use App\Http\Controllers\AtletaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Routes for Guest users (not logged in)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::get('/', function () {
    // Check if a user is NOT logged in
    if (auth()->guest()) {
        return redirect()->route('login');
    }

    // If they are logged in, send them to the dashboard (or wherever)
    return redirect()->route('dashboard');
});

// Routes for Authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('atletas', AtletaController::class);
    Route::patch('atletas/{atleta}/toggle-status', [AtletaController::class, 'toggleStatus'])
        ->name('atletas.toggle-status');
    Route::patch('atletas/{id}/restore', [AtletaController::class, 'restore'])
        ->name('atletas.restore')
        ->withTrashed();


    // Logout route
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});