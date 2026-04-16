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
