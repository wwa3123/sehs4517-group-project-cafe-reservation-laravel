<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;

use App\Http\Controllers\EventController;
use App\Http\Controllers\MenuController;
use App\Models\ReservedSlot;

Route::get('/', function () {
    return view('intro');
})->name('home');

Route::get('/menu', [MenuController::class, 'index'])->name('menu');

Route::get('/register', [RegistrationController::class, 'show'])->name('register');
Route::post('/register', [RegistrationController::class, 'register']);
Route::get('/check-email', [RegistrationController::class, 'checkEmail']);


Route::middleware(['auth'])->group(function() {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
});

// TANG Zikun's routes
require __DIR__.'/web_login_and_history.php';


// Reservation thank you page
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
})->middleware(['auth', 'throttle:30,1'])->name('api.booked-slots');

Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');

    Route::middleware(['auth'])->group(function () {
        Route::get('/create', [EventController::class, 'create'])->name('create')->middleware('admin');
        Route::post('/', [EventController::class, 'store'])->name('store')->middleware('admin');
        Route::post('/{event}/join', [EventController::class, 'join'])->name('join');
    }); // end auth

    Route::get('/{event}', [EventController::class, 'show'])->name('show');
}); // end events prefix