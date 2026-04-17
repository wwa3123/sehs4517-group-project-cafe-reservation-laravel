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
            $request->session()->put('member_id', $member->member_id);
            $request->session()->put('member_name', $member->first_name . ' ' . $member->last_name);
            $request->session()->put('member_email', $member->email);
            $request->session()->put('member_role', $member->role);

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
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();        

        return redirect()->route('login');  
    }
}