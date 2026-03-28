<?php

namespace App\Http\Controllers;

use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\RegistrationRequest;


class RegistrationController extends Controller {
    public function show() {
        if (Auth::check()) {
            return redirect('/profile');
        }

        return view('register');
    }

    public function register(RegistrationRequest $request) {

        $user = User::registerUser($request->validated());

        Auth::login($user);

        $request->session()->forget('_old_input');
        return redirect('/profile')->with('registered', 'Successfully Registered!');
    }

    public function checkEmail(Request $request) {
        $exists = DB::table('users')->where('email', $request->input('email'))->exists();
        return response()->json(['exists' => $exists]);
    }
}
