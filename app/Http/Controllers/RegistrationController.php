<?php

namespace App\Http\Controllers;

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
            'email' => 'required|email|unique:members,email',
            'password' => 'required|min:8|confirmed',
        ], ['password.min' => 'Your password must be at least 8 characters long.', 'password.confirmed' => 'Password does not match', 'email.unique' => 'Email already registered.']);

        
        DB::table('members')->insert([
            'first_name'=> $validated['first_name'],
            'last_name'=> $validated['last_name'],
            'address'=> $validated['address'],
            'phone' => $validated['phone'],
            'email'=> $validated['email'],
            'password_hash'=> Hash::make($validated['password']),
            'subscribe_events' => $request->boolean('subscribe'),
            'loyalty_points'=> 0,
            'created_at'=> now(),
            'updated_at'=> now()
        ]);

        $request->session()->forget('_old_input');
        return back(); // todo: proceed to login
    }

    public function checkEmail(Request $request) {
        $exists = DB::table('members')->where('email', $request->input('email'))->exists();
        return response()->json(['exists' => $exists]);
    }
} 
