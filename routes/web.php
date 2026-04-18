<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\ReservationController;
use App\Models\ReservedSlot;

// --- Public --------------------------------------------------------------------

Route::get('/', fn () => view('intro'))->name('home');
Route::get('/menu', [MenuController::class, 'index'])->name('menu');

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [LoginController::class, 'verify'])->name('login.verify')->middleware('throttle:5,1');

Route::get('/register', [RegistrationController::class, 'show'])->name('register');
Route::post('/register', [RegistrationController::class, 'register']);
Route::get('/check-email', [RegistrationController::class, 'checkEmail']);

// --- Authenticated -------------------------------------------------------------

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);

    Route::get('/reservation/history', [HistoryController::class, 'index'])->name('reservation.history');

    Route::get('/reservation/thankyou', function () {
        return view('reservation-thankyou', [
            'email'           => session('email', ''),
            'date'            => session('date', ''),
            'timeSlot'        => session('timeSlot', ''),
            'table'           => session('table', ''),
            'earnedTokens'    => session('earnedTokens', 0),
            'discountApplied' => session('discountApplied', 0),
            'gameSuggestions' => session('gameSuggestions', []),
        ]);
    })->name('reservation.thankyou');

    // Returns booked time_slot IDs for a given table + date (used by JS in create forms)
    Route::get('/api/booked-slots', function (Request $request) {
        $request->validate([
            'table_id' => ['required', 'integer'],
            'date'     => ['required', 'date'],
        ]);

        $booked = ReservedSlot::where('table_id', $request->input('table_id'))
            ->whereHas('reservation', fn ($q) => $q->whereDate('date', $request->input('date')))
            ->pluck('time_slots_id');

        return response()->json($booked);
    })->middleware('throttle:30,1')->name('api.booked-slots');

    // --- Reservations ----------------------------------------------------------

    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [ReservationController::class, 'index'])->name('index');
        Route::get('/create', [ReservationController::class, 'create'])->name('create');
        Route::post('/', [ReservationController::class, 'store'])->name('store');
        Route::get('/{reservation}', [ReservationController::class, 'show'])->name('show');
        Route::delete('/{reservation}', [ReservationController::class, 'destroy'])->name('destroy')->middleware('admin');
    });

    // --- Events (auth required) ------------------------------------------------

    Route::prefix('events')->name('events.')->group(function () {
        Route::get('/create', [EventController::class, 'create'])->name('create')->middleware('admin');
        Route::post('/', [EventController::class, 'store'])->name('store')->middleware('admin');
        Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit')->middleware('admin');
        Route::put('/{event}', [EventController::class, 'update'])->name('update')->middleware('admin');
        Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy')->middleware('admin');
        Route::post('/{event}/join', [EventController::class, 'join'])->name('join');
    });
});

// --- Events index & show (public) ---------------------------------------------

Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/{event}', [EventController::class, 'show'])->name('show');
});
