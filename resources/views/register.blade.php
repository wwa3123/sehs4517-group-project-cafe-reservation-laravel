@extends('layouts.app')
@section('title', 'Register')
@push('head')
    @vite('resources/js/validation.js')
@endpush
@section('content')

    <div class="max-w-md mx-auto px-4 py-10">
    <form action="/register" method="POST" id="reg" class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8">
        @csrf
        <nav class="mb-4 flex gap-4 text-blue-600">
            <a href="/">Back</a>
        </nav>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <input type="text" name="first_name" value="{{ old("first_name") }}" placeholder="First Name" required
                class="border p-2">
            <input type="text" name="last_name" value="{{ old("last_name") }}" placeholder="Last Name" required
                class="border p-2">
        </div>

        <input type="text" name="address" value="{{ old("last_name") }}" placeholder="Address" required
            class="w-full border p-2 mb-4">
        <input type="tel" name="phone" value="{{ old("last_name") }}" placeholder="Phone Number" required
            class="w-full border p-2 mb-4">

        <input type="email" id="email" name="email" value="{{ old("email") }}" placeholder="Email" required
            class="w-full border p-2">
        <p id="email-msg" class="text-red-500 text-sm {{ $errors->has('email') ? '' : 'mb-4' }} errormsg">
            {{ $errors->first('email') }}</p>

        <input type="password" id="setpassword" name="password" placeholder="Password" required class="w-full border p-2">
            <p id="password-msg" class="text-red-500 text-sm errormsg">{{ $errors->first('password') }}</p>
        <div id="strength-bar" class="h-1 w-full bg-gray-200 {{ $errors->has('password') ? '' : 'mb-4' }}">
            <div id="strength-fill" class="h-full w-0 transition-all"></div>
        </div>

        <input type="password" name="password_confirmation" placeholder="Confirm Password" required
            class="w-full border p-2 mb-4">

        <label class="flex items-center mb-4">
            <input type="checkbox" name="subscribe" class="mr-2"> Subscribe to game night events
        </label>

        <div class="flex gap-2 mb-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Register</button>
            <button type="reset" id="reset" class="bg-gray-300 px-4 py-2 rounded">Clear</button>
        </div>

        Already have an account?
        <a href="/login" class="text-blue-600">Login</a>
    </form>
    </div>
@endsection