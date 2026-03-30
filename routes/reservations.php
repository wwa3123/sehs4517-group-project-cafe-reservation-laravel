<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReservationController;

/*
|--------------------------------------------------------------------------
| Reservation Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the reservation
| system. It's a breeze. Simply tell Laravel the URIs it should
| respond to and give it the controller to call when that URI is requested.
|
*/

// Admin route to get all reservations

Route::prefix('reservations')->name('reservations.')->group(function () {

    Route::get('/', [ReservationController::class, 'index'])->name('index');

    Route::get('/create', [ReservationController::class, 'create'])->name('create');

    Route::post('/', [ReservationController::class, 'store'])->name('store');

    Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');

    });
