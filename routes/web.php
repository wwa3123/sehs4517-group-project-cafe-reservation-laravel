<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/tailwind', function () {
    return view('tailwindTest');
});

Route::get('/register', function() {
    return view('register');
});
Route::post('/register', [RegistrationController::class, 'register']);
Route::get('/check-email', [RegistrationController::class, 'checkEmail']);
Route::post('/forget-old', function() {
    session()->forget('_old_input');
    return response()->json(['status' => 'success']);
});