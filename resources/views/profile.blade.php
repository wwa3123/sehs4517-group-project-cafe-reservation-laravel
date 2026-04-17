@extends('layouts.app')
@section('title', 'Profile')
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
    <div class="max-w-2xl w-full rounded-3xl border shadow-xl p-10 mx-auto my-8 transition-all sm:p-7 form-card">
        @if (session()->has('success'))
            <div class="p-3.5 rounded-2xl mb-6 font-semibold accent-box border border-accent" style="border-color: var(--accent); color: var(--text-secondary);">
                ✓ {{ session('success') }}
            </div>
        @endif

        <h2 class="text-4xl font-bold text-transparent bg-clip-text mb-7 pb-4 border-b -tracking-wide" style="background-image: linear-gradient(135deg, var(--accent, #4c9f2f) 0%, #7ac74f 100%); border-color: var(--accent-tint);">Your Profile</h2>

        <form action="/profile" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4.5 mb-4 md:grid-cols-2">
                <div>
                    <label for="first_name" class="form-label block text-sm font-semibold mb-2 tracking-wide">First Name</label>
                    @if($editing == 1)
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
                    @else
                        <div class="px-3.5 py-3 rounded-2xl text-sm accent-box" style="color: var(--text-primary);">{{ $user->first_name }}</div>
                    @endif
                </div>
                <div>
                    <label for="last_name" class="form-label block text-sm font-semibold mb-2 tracking-wide">Last Name</label>
                    @if($editing == 1)
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
                    @else
                        <div class="px-3.5 py-3 rounded-2xl text-sm accent-box" style="color: var(--text-primary);">{{ $user->last_name }}</div>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <label for="email" class="form-label block text-sm font-semibold mb-2 tracking-wide">Email Address</label>
                @if($editing == 1)
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
                    @if($errors->has('email'))
                        <p class="form-error text-sm mt-1">{{ $errors->first('email') }}</p>
                    @endif
                @else
                    <div class="px-3.5 py-3 rounded-2xl text-sm accent-box" style="color: var(--text-primary);">{{ $user->email }}</div>
                @endif
            </div>

            <div class="mb-4">
                <label for="phone" class="form-label block text-sm font-semibold mb-2 tracking-wide">Phone</label>
                @if($editing == 1)
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
                @else
                    <div class="px-3.5 py-3 rounded-2xl text-sm accent-box" style="color: var(--text-primary);">{{ $user->phone }}</div>
                @endif
            </div>

            <div class="mb-7">
                <label for="address" class="form-label block text-sm font-semibold mb-2 tracking-wide">Address</label>
                @if($editing == 1)
                    <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
                @else
                    <div class="px-3.5 py-3 rounded-2xl text-sm accent-box" style="color: var(--text-primary);">{{ $user->address ?? 'Not provided' }}</div>
                @endif
            </div>

            <div class="flex gap-3 sm:flex-col-reverse">
                @if($editing == 1)
                    <a href="/profile" class="btn-secondary flex-1 py-3 px-4 text-sm font-bold rounded-2xl text-center">Cancel</a>
                    <button type="submit" class="btn-primary flex-1 py-3 px-4 text-sm font-bold rounded-2xl">Update Profile</button>
                @elseif($editing == 2)
                @else
                    <a href="/profile?edit=1" class="btn-primary w-full py-3 px-4 text-sm font-bold rounded-2xl text-center">Edit Profile</a>
                @endif
            </div>

            @if($editing != 1 && $editing != 2)
                <div class="flex gap-3 mt-3 sm:flex-col-reverse">
                    <a href="/profile/?edit=2" class="btn-secondary w-full py-3 px-4 text-sm font-bold rounded-2xl text-center">Change Password</a>
                </div>
            @endif
        </form>

        @if($editing == 2)
            <div class="rounded-3xl p-6 mt-6 sm:p-4.5 accent-box">
                <h3 class="text-lg font-bold mb-4.5" style="color: var(--text-secondary);">Change Password</h3>
                <form action="/profile/password" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label for="current_password" class="form-label block text-sm font-semibold mb-2 tracking-wide">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
                        @if($errors->has('current_password'))
                            <p class="form-error text-xs mt-1">{{ $errors->first('current_password') }}</p>
                        @endif
                    </div>

                    <div class="mb-4">
                        <label for="setpassword" class="form-label block text-sm font-semibold mb-2 tracking-wide">New Password</label>
                        <input type="password" id="setpassword" name="password" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
                        <div id="strength-bar" class="h-1 w-full mb-4 hidden">
                            <div id="strength-fill" class="h-full w-0 bg-red-500 transition-all"></div>
                        </div>
                        @if($errors->has('password'))
                            <p class="form-error text-xs mt-1">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="form-label block text-sm font-semibold mb-2 tracking-wide">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="form-input w-full px-3.5 py-3 border-2 rounded-2xl">
                        @if($errors->has('password_confirmation'))
                            <p class="form-error text-xs mt-1">{{ $errors->first('password_confirmation') }}</p>
                        @endif
                    </div>

                    <div class="flex gap-3 sm:flex-col-reverse">
                        <a href="/profile" class="btn-secondary flex-1 py-3 px-4 text-sm font-bold rounded-2xl text-center">Cancel</a>
                        <button type="submit" class="btn-primary flex-1 py-3 px-4 text-sm font-bold rounded-2xl">Confirm Password Change</button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <div class="max-w-4xl w-full mx-auto mb-8 px-4">
        <h3 class="text-sm font-bold mb-3 tracking-widest uppercase" style="color: var(--text-secondary);">Quick Links</h3>
        <a href="{{ route('reservation.history') }}" class="inline-flex items-center gap-2 px-4.5 py-2.5 border-2 rounded-2xl text-sm font-semibold no-underline transition-all text-accent" style="border-color: var(--border);">📋 My Reservation History</a>
    </div>
@endsection