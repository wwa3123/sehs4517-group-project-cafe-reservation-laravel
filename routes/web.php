<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Models\ReservedSlot;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tailwind', function () {
    return view('tailwindTest');
});

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
})->name('api.booked-slots');

Route::prefix('events')->name('events.')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/create', [EventController::class, 'create'])->name('create');
    Route::post('/', [EventController::class, 'store'])->name('store');
    Route::get('/{event}', [EventController::class, 'show'])->name('show');
    Route::post('/{event}/join', [EventController::class, 'join'])->name('join');
});