<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HistoryController;

// login routes
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'verify'])->name('login.verify')->middleware('throttle:5,1');
Route::middleware('auth')->group(function() {
    Route::get('/reservation/history', [HistoryController::class, 'index'])->name('reservation.history');
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
});