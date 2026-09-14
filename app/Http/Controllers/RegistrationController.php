<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegistrationRequest;
use App\Models\Member;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function show()
    {
        if (Auth::check()) {
            return redirect('/profile');
        }

        return view('register');
    }

    public function register(RegistrationRequest $request)
    {

        $user = Member::registerMember($request->validated());

        Auth::login($user);

        $request->session()->forget('_old_input');

        return redirect('/profile')->with('success', 'Successfully Registered!');
    }
}
