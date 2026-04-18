@extends('layouts.app')
@section('title', 'Register')
@push('head')
    @vite('resources/js/validation.js')
@endpush
@section('content')
    <style>
        .form-card {
            background-color: var(--card-bg);
            border-color: var(--border);
        }

        .form-label {
            color: var(--text-secondary);
        }

        .form-input {
            background-color: var(--card-bg);
            color: var(--text-primary);
            border-color: #d0d5cf;
            transition: all 0.2s ease;
        }

        body.app-page.dark .form-input {
            border-color: var(--border);
        }

        .form-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px var(--accent-tint);
            outline: none;
        }

        .accent-box {
            background-color: var(--accent-tint);
        }

        .form-error {
            color: #ef4444;
        }

        .btn-primary {
            background-color: var(--accent);
            color: white;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background-color: var(--accent-dark);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(76, 159, 47, 0.3);
        }

        .btn-primary:active {
            background-color: var(--accent);
            transform: translateY(0);
            box-shadow: none;
        }

        .btn-secondary {
            background-color: var(--border);
            color: var(--text-secondary);
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #cde2d5;
        }

        body.app-page.dark .btn-secondary:hover {
            background-color: #334a2f;
        }

        .text-accent {
            color: var(--accent);
        }

        .text-accent:hover {
            background-color: var(--accent-tint);
            border-color: var(--accent);
            transform: translateY(-2px);
        }
    </style>
    <div class="max-w-xl w-full rounded-3xl border shadow-xl p-10 mx-auto my-8 transition-all form-card">
        <h1 class="text-4xl font-bold text-transparent bg-clip-text mb-3 text-center -tracking-wide" style="background-image: linear-gradient(135deg, var(--accent, #4c9f2f) 0%, #7ac74f 100%);">Create Account</h1>
        <p class="text-center text-sm mb-7 pb-5 border-b" style="color: var(--text-muted); border-color: var(--border);">Join Chit-Chat Café and start booking tables</p>

        <form action="/register" method="POST" id="reg">
            @csrf

            <div class="grid grid-cols-1 gap-3.5 mb-4 md:grid-cols-2">
                <div>
                    <label for="first_name" class="form-label block text-sm font-semibold mb-2 tracking-wide">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
                </div>
                <div>
                    <label for="last_name" class="form-label block text-sm font-semibold mb-2 tracking-wide">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
                </div>
            </div>

            <div class="mb-4">
                <label for="address" class="form-label block text-sm font-semibold mb-2 tracking-wide">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
            </div>

            <div class="mb-4">
                <label for="phone" class="form-label block text-sm font-semibold mb-2 tracking-wide">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
            </div>

            <div class="mb-4">
                <label for="email" class="form-label block text-sm font-semibold mb-2 tracking-wide">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
                <p id="email-msg" class="form-error text-sm mb-4">{{ $errors->first('email') }}</p>
            </div>

            <div class="mb-4">
                <label for="setpassword" class="form-label block text-sm font-semibold mb-2 tracking-wide">Password</label>
                <input type="password" id="setpassword" name="password" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl mb-0">
                <p id="password-msg" class="form-error text-sm mb-0">{{ $errors->first('password') }}</p>
                <div id="strength-bar" class="h-1 w-full {{ $errors->has('password') ? '' : 'mb-4' }} hidden">
                    <div id="strength-fill" class="h-full w-0 bg-red-500 transition-all"></div>
                </div>
            </div>

            <div class="mb-5">
                <label for="password_confirmation" class="form-label block text-sm font-semibold mb-2 tracking-wide">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
            </div>

            <div class="flex items-center gap-2.5 p-3.5 rounded-2xl mb-7 accent-box">
                <input type="checkbox" id="subscribe" name="subscribe_events" class="w-4.5 h-4.5 cursor-pointer accent" style="accent-color: var(--accent);">
                <label for="subscribe" class="text-sm font-medium cursor-pointer" style="color: var(--text-secondary);">Subscribe to game night events</label>
            </div>

            <div class="flex gap-3 mb-6 sm:flex-col-reverse">
                <button type="reset" id="reset" class="btn-secondary flex-1 py-3 px-4 text-sm font-bold rounded-2xl">Clear</button>
                <button type="submit" class="btn-primary flex-1 py-3 px-4 text-sm font-bold rounded-2xl">Register</button>
            </div>
        </form>

        <div class="text-center text-sm" style="color: var(--text-muted);">
            Already have an account? <a href="/login" class="font-semibold no-underline transition-colors hover:underline text-accent">Sign in here</a>
        </div>
    </div>
@endsection