@extends('layouts.app')
@section('title', 'Login Failed')
@push('head')
<style>
        .error-card {
            max-width: 500px;
            width: 100%;
            background-color: var(--card-bg, #ffffff);
            border-radius: 40px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.05), 0 4px 8px rgba(0, 0, 0, 0.02);
            padding: 48px 36px 52px;
            border: 1px solid var(--border, #ddebe0);
            text-align: center;
            animation: errFadeUp 0.5s ease-out;
        }

        .error-card .brand h1 {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            background: linear-gradient(135deg, var(--accent, #4c9f2f) 0%, #7ac74f 100%);
            background-clip: text;
            -webkit-background-clip: text;
            color: transparent;
            margin-bottom: 16px;
        }

        .error-icon { font-size: 4rem; margin-bottom: 20px; }

        .error-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #e74c3c;
            margin-bottom: 12px;
        }

        .error-message {
            font-size: 1rem;
            color: #3a5a34;
            background-color: #fee9e6;
            border-radius: 16px;
            padding: 14px 20px;
            margin: 24px 0 28px;
            text-align: left;
        }

        .back-btn {
            display: inline-block;
            background-color: var(--accent, #4c9f2f);
            border: none;
            border-radius: 40px;
            padding: 14px 32px;
            font-size: 1rem;
            font-weight: 700;
            color: white;
            cursor: pointer;
            transition: all 0.25s ease;
            text-decoration: none;
            letter-spacing: 0.5px;
        }

        .back-btn:hover {
            background-color: var(--accent-dark, #3b7e24);
            transform: translateY(-2px);
            box-shadow: 0 8px 18px rgba(76, 159, 47, 0.25);
        }

        @keyframes errFadeUp {
            from { opacity: 0; transform: translateY(18px); }
            to   { opacity: 1; transform: translateY(0); }
        }
</style>
@endpush
@section('content')
<div class="flex justify-center py-16 px-6">
    <div class="error-card">
        <div class="brand">
            <h1>Chit-Chat Café</h1>
        </div>
        <div class="error-icon">❌</div>
        <div class="error-title"><h2>Login Failed</h2></div>
        <div class="error-message">
            <h4>Email or password is incorrect. Please try again.</h4>
        </div>
        <a href="/login" class="back-btn">Back to Login</a>
    </div>
</div>
@endsection