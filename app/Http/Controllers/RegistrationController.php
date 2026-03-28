<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function register(Request $request) {
        $validated = $request -> validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ], ['password.min' => 'Your password must be at least 8 characters long.', 'password.confirmed' => 'Password does not match', 'email.unique' => 'Email already registered.']);

        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'address' => $validated['address'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'password_hash' > Hash::make($validated['password']),
            'subscribe_events' => $request->boolean('subscribe'),
            'loyalty_points' => 0,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Auth::login($user);
        
        $request->session()->forget('_old_input');
        return back(); // todo: proceed to login
    }

    public function checkEmail(Request $request) {
        $exists = DB::table('users')->where('email', $request->input('email'))->exists();
        return response()->json(['exists' => $exists]);
    }
} 
