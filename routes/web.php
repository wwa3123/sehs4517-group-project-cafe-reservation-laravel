<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/tailwind', function () {
    return view('tailwindTest');
});

Route::get('/register', [RegistrationController::class, 'show']);
Route::post('/register', [RegistrationController::class, 'register']);
Route::get('/check-email', [RegistrationController::class, 'checkEmail']);
Route::post('/forget-old', function() {
    session()->forget('_old_input');
    return response()->json(['status' => 'success']);
});


Route::middleware(['auth'])->group(function() {
    Route::get('/profile', [ProfileController::class, 'show']);
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