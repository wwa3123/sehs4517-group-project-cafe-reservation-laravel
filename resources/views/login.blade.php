@extends('layouts.app')
@section('title', 'Login · Chit-Chat Café')
@section('bodyClass', 'login-page')
@push('head')
    @vite(['resources/css/login.css'])
@endpush
@section('content')
<div class="login-card">
        <div class="brand">
            <h1>Chit-Chat Cafe</h1>
            <hr>
            <div class="slogan"><h3>Your home for games and gatherings</h3></div>
        </div>
        

        <div class="welcome-text">
            <p>Welcome back · Sign in to your account</p>
        </div>

        <form class="login-form" id="loginForm" method="POST" action="{{ route('login.verify') }}">
            @csrf
            
            @if($errors->any())
            <div style="background:#fee9e6; color:#e74c3c; padding:14px 18px; border-radius:20px; text-align:center; font-weight:600;">
                {{ $errors->first() }}
            </div>
            @endif

            <!-- Email -->
            <div class="input-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autocomplete="email">
            </div>

            <!-- password -->
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <!-- remember me + forgot password -->
            <div class="form-aux">
                <label class="checkbox-group">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Remember me</span>
                </label>
                <a href="#" class="forgot-link" id="forgotPwdLink">Forgot password?</a>
            </div>

            <!-- login button -->
            <button type="submit" class="login-btn">Login</button>

            <div class="register-area">
                <div class="register-text">
                    First time in here?
                    <a href="{{ route('register') }}" class="register-link" id="registerLink">Register now!</a>
                </div>
            </div>

        </form>
    </div>
@endsection