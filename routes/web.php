<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

// Authentication routes (Standard Login & Logout)
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Main COBIT Explorer Protected Routes (All authenticated users have access without roles)
Route::middleware('auth')->group(function () {
    Route::livewire('/', 'cobit-overview')
        ->name('cobit.overview');

    Route::livewire('/overview', 'cobit-overview')
        ->name('cobit.overview.alias');

    Route::livewire('/cobit-framework', 'cobit-explorer')
        ->name('cobit.framework');

    Route::get('/cobit-explorer', fn() => redirect()->route('cobit.framework'))
        ->name('cobit.explorer');

    Route::livewire('/raci-matrix', 'raci-matrix')
        ->name('raci.matrix');

    Route::livewire('/research-methodology', 'research-hub')
        ->name('research.hub');
});
