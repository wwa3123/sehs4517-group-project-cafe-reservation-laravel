<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    // login page
    public function login()
    {
        return view('login'); 
    }

    // login verification
    public function verify(Request $request)
    {
        $email = $request->email;
        $password = $request->password;

        $member = DB::table('members')->where('email', $email)->first();

        if ($member && Hash::check($password, $member->password_hash)) {
            Session::put('member_id', $member->member_id);
            Session::put('member_name', $member->first_name . ' ' . $member->last_name);
            
            return redirect()->route('reserve');// 假定預約頁面路由名稱為reserve
        } else {
            return redirect()->route('login.failed');
        }
    }

    // login failed page
    public function failed()
    {
        return view('login_failed');  
    }

    // logout function
    public function logout()
    {
        Session::flush();
        return redirect()->route('login');  
    }
}