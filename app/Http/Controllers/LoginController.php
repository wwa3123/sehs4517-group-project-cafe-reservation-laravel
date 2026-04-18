<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // login page
    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('reservations.index');
        }

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

            $member = Auth::user();

            // If remember me was not checked, clear any stored remember token
            if (!$request->boolean('remember')) {
                $member->setRememberToken(null);
                $member->save();
            }

            return redirect()->route('reservations.index');
        } else {
            return back()->withErrors([
                'email' => 'Invalid email or password.'
            ])->withInput($request->only('email'));
        }
    }

    // logout function
    public function logout(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->setRememberToken(null);
            $user->save();
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}