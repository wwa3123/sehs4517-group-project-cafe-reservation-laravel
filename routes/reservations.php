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

Route::prefix('reserve')->name('reserve.')->group(function () {
    Route::get('/all', [ReservationController::class, 'getAllReservation'])->name('all');
    
    // Shows the form to create a reservation
    Route::get('/create', [ReservationController::class, 'create'])->name('create');

    // Stores the new reservation
    Route::post('/', [ReservationController::class, 'store'])->name('store');

    // Shows a specific reservation confirmation
    Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');

    // Shows a list of the user's reservations
    Route::get('/', [ReservationController::class, 'index'])->name('index');
    });
