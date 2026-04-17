@extends('layouts.app')
@section('title', 'Register')
@push('head')
    @vite('resources/js/validation.js')
@endpush
@section('content')
    <div class="max-w-xl w-full rounded-3xl border shadow-xl p-10 mx-auto my-8 transition-all" style="background-color: var(--card-bg); border-color: var(--border);">
        <h1 class="text-4xl font-bold text-transparent bg-clip-text mb-3 text-center -tracking-wide" style="background-image: linear-gradient(135deg, var(--accent, #4c9f2f) 0%, #7ac74f 100%);">Create Account</h1>
        <p class="text-center text-sm mb-7 pb-5 border-b" style="color: var(--text-muted); border-color: var(--border);">Join Chit-Chat Café and start booking tables</p>

        <form action="/register" method="POST" id="reg">
            @csrf

            <div class="grid grid-cols-1 gap-3.5 mb-4 md:grid-cols-2">
                <div>
                    <label for="first_name" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">First Name</label>
                    <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" required class="w-full px-3.5 py-3 border-2 border-gray-300 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">Last Name</label>
                    <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}" required class="w-full px-3.5 py-3 border-2 border-gray-300 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
                </div>
            </div>

            <div class="mb-4">
                <label for="address" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">Address</label>
                <input type="text" id="address" name="address" value="{{ old('address') }}" required class="w-full px-3.5 py-3 border-2 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" required class="w-full px-3.5 py-3 border-2 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required class="w-full px-3.5 py-3 border-2 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
                <p id="email-msg" class="text-red-500 text-sm mb-4 errormsg">{{ $errors->first('email') }}</p>
            </div>

            <div class="mb-4">
                <label for="setpassword" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">Password</label>
                <input type="password" id="setpassword" name="password" required class="w-full px-3.5 py-3 border-2 rounded-2xl transition-all focus:outline-none mb-0" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
                <p id="password-msg" class="text-red-500 text-sm mb-0 errormsg">{{ $errors->first('password') }}</p>
                <div id="strength-bar" class="h-1 w-full {{ $errors->has('password') ? '' : 'mb-4' }} hidden">
                    <div id="strength-fill" class="h-full w-0 bg-red-500 transition-all"></div>
                </div>
            </div>

            <div class="mb-5">
                <label for="password_confirmation" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3.5 py-3 border-2 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
            </div>

            <div class="flex items-center gap-2.5 p-3.5 rounded-2xl mb-7" style="background-color: var(--accent-tint);">
                <input type="checkbox" id="subscribe" name="subscribe" class="w-4.5 h-4.5 cursor-pointer accent" style="accent-color: var(--accent);">
                <label for="subscribe" class="text-sm font-medium cursor-pointer" style="color: var(--text-secondary);">Subscribe to game night events</label>
            </div>

            <div class="flex gap-3 mb-6 sm:flex-col-reverse">
                <button type="reset" id="reset" class="flex-1 py-3 px-4 text-sm font-bold rounded-2xl transition-all tracking-wide" style="background-color: var(--border); color: var(--text-secondary);" onmouseover="this.style.backgroundColor='#cde2d5'" onmouseout="this.style.backgroundColor='var(--border)'">Clear</button>
                <button type="submit" class="flex-1 py-3 px-4 text-sm font-bold rounded-2xl text-white transition-all -translate-y-0 tracking-wide" style="background-color: var(--accent);" onmouseover="this.style.backgroundColor='var(--accent-dark)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(76, 159, 47, 0.3)'" onmouseout="this.style.backgroundColor='var(--accent)'; this.style.transform='translateY(0)'; this.style.boxShadow=''">Register</button>
            </div>
        </form>

        <div class="text-center text-sm" style="color: var(--text-muted);">
            Already have an account? <a href="/login" class="font-semibold no-underline transition-colors hover:underline" style="color: var(--accent);">Sign in here</a>
        </div>
    </div>
@endsection