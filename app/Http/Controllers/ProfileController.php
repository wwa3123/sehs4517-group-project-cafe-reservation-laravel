<?php

namespace App\Http\Controllers;

use App\Http\Requests\PasswordUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller {
    public function show(Request $request) {
        return view('profile', [
            'user' => Auth::user(),
            'editing' => $request->query('edit')
        ]);
    }

    public function update(ProfileUpdateRequest $request) {
        $user = Auth::user();
        $user->update($request->validated());

        $request->request->remove('edit');
        return redirect('/profile')->with('success', 'Profile updated!');
    }

    public function updatePassword(PasswordUpdateRequest $request) {
        $user = Auth::user();

        $user->update([
            'password_hash' => Hash::make($request->input('password'))
        ]);

        $request->request->remove('edit');
        return redirect('/profile')->with('success', 'Password updated!');
    }
}
