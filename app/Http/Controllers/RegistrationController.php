<?php

namespace App\Http\Controllers;

use App\Models\Member;
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

        $user = Member::registerMember($request->validated());

        Auth::login($user);

        $request->session()->forget('_old_input');
        return redirect('/profile')->with('success', 'Successfully Registered!');
    }

    public function checkEmail(Request $request) {
        $exists = DB::table('members')->where('email', $request->input('email'))->exists();
        return response()->json(['exists' => $exists]);
    }
}
