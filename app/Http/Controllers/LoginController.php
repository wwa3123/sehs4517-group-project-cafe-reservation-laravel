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
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $member = Member::where('email', $request->email)->first();

        if ($member && Hash::check($request->password, $member->password_hash)) {
            $request->session()->regenerate();
            Session::put('member', [
                'id' => $member->member_id,
                'first_name' => $member->first_name,
                'last_name' => $member->last_name,
            ]);
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