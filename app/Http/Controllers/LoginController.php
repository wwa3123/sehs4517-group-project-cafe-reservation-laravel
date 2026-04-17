<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // login page
    public function login()
    {
        return view('login'); 
    }

    public function verify(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials, $request->boolean("remember"))) {
            $request->session()->regenerate();

            return redirect()->route('reserve');
        } else {
            return back()->withErrors([
                'email' => 'Invalid email or password.'
            ])->withInput($request->only('email'));
        }
    }

    // logout function
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();        

        return redirect()->route('login');  
    }
}