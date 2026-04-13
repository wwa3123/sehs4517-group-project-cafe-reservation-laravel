<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HistoryController;

// login routes
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login/verify', [LoginController::class, 'verify'])->name('login.verify');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// reservation history route
Route::get('/reservation/history', [HistoryController::class, 'index'])
    ->name('reservation.history')
    ->middleware('check.member'); 