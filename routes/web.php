<?php

use App\Http\Controllers\AuthController;
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
    // The main app management page
    Route::get('/dashboard', function () {
        // You can access the authenticated user like this:
        $user = Auth::user();
        return view('dashboard', compact('user'));
    })->name('dashboard');

    Route::get('/sobre', function () {
        // You can access the authenticated user like this:
        $user = Auth::user();
        return view('sobre', compact('user'));
    })->name('sobre');
    // Logout route
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});