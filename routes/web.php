<?php

use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;

use App\Http\Controllers\EventController;
use App\Models\ReservedSlot;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tailwind', function () {
    return view('tailwindTest');
});

Route::get('/register', [RegistrationController::class, 'show'])->name('register');
Route::post('/register', [RegistrationController::class, 'register']);
Route::get('/check-email', [RegistrationController::class, 'checkEmail']);
Route::post('/forget-old', function() {
    session()->forget('_old_input');
    return response()->json(['status' => 'success']);
});


Route::middleware(['auth'])->group(function() {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
});

// TANG Zikun's routes
require __DIR__.'/web_login_and_history.php';


// Reservation thank you page
Route::get('/reservation/thankyou', function () {

//fetch some popular games from database
$popularGames = \App\Models|Game::inRandomOrder()->limit(3)->get(['title'])->pluck('title')->toArray();


//if no games in database yet, use fallback
if (empty($popularGames)) {
    $popularGames = ['Catan', 'Ticket to Ride', 'Codenames'];
}

    // In a real app, this would come from a session or database
    return view('reservation-thankyou', [
        'email' => session('email', 'guest@example.com'),
        'date' => session('date', 'April 16, 2026'),
        'timeSlot' => session('timeSlot', '2:00 PM - 4:00 PM'),
        'table' => session('table', 'Gaming Table 1'),
        'showQrCode' => false, // Set to true to show QR code
        'gameSuggestions' => $popularGames, // Optional game suggestions
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
})->middleware(['auth'])->name('api.booked-slots');

Route::prefix('events')->name('events.')->middleware(['auth'])->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('index');
    Route::get('/create', [EventController::class, 'create'])->name('create');
    Route::post('/', [EventController::class, 'store'])->name('store');
    Route::get('/{event}', [EventController::class, 'show'])->name('show');
    Route::post('/{event}/join', function (Request $request, $event) {
        abort_unless(auth()->check(), 403);

        $request->merge([
            'member_id' => auth()->id(),
        ]);

        return app(EventController::class)->join($request, $event);
    })->name('join');
});