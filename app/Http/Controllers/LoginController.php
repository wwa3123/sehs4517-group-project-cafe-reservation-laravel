<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // login page
    public function login()
    {
        return view('login'); 
    }

    public function verify(Request $request)
    {
            $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // TODO: add $table->rememberToken(); to the member migration for remember me functionailities
        // if (Auth::attempt($credentials, $request->boolean("remember"))) {
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('reserve');
        } else {
            return back()->withErrors([
                'email' => 'Invalid email or password.'
            ])->withInput($request->only('email'));
        }
    }

    // logout function
    public function logout()
    {
        Session::flush();
        return redirect()->route('login');  
    }
}