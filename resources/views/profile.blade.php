@extends('layouts.app')
@section('title', 'Profile')
@push('head')
    @vite('resources/js/validation.js')
@endpush
@section('content')
    <div class="max-w-2xl mx-auto px-4 py-10 sm:px-6">
        @if (session()->has('success'))
            <h1 class="text-3xl font-bold mb-6 pb-2">
                {{ session('success') }}
            </h1>
        @endif

        @foreach ($errors->all() as $error)
            <p class="text-red-500">Error: {{ $error }}</p>
        @endforeach

        <h2 class="text-2xl font-bold mb-6 border-b pb-2">Your Profile</h2>

        <form action="/profile" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700">First Name</label>
                        @if($editing == 1)
                            <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}"
                                class="w-full border p-2 rounded" required>
                        @else
                            <p class="p-2 bg-gray-50 rounded">{{ $user->first_name }}</p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700">Last Name</label>
                        @if($editing == 1)
                            <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}"
                                class="w-full border p-2 rounded" required>
                        @else
                            <p class="p-2 bg-gray-50 rounded">{{ $user->last_name }}</p>
                        @endif
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Email Address</label>
                    @if($editing == 1)
                        <input type="email" name="email" value="{{ old('email', $user->email) }}"
                            class="w-full border p-2 rounded" required>
                    @else
                        <p class="p-2 bg-gray-50 rounded">{{ $user->email }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Phone</label>
                    @if($editing == 1)
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                            class="w-full border p-2 rounded" required>
                    @else
                        <p class="p-2 bg-gray-50 rounded">{{ $user->phone }}</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700">Address</label>
                    @if($editing == 1)
                        <input type="text" name="address" value="{{ old('address', $user->address) }}"
                            class="w-full border p-2 rounded" required>
                    @else
                        <p class="p-2 bg-gray-50 rounded">{{ $user->address ?? 'Not provided' }}</p>
                    @endif
                </div>
            </div>

            <div class="mt-4 flex gap-3">
                @if($editing == 1)
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-[var(--accent,#4c9f2f)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--accent-dark,#3b7e24)] transition-colors">Update Profile</button>
                    <a href="/profile" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
                @elseif($editing == 2)
                @else
                    <a href="/profile?edit=1" class="inline-flex items-center justify-center rounded-lg bg-[var(--accent,#4c9f2f)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--accent-dark,#3b7e24)] transition-colors">Edit Profile</a>
                    <a href="/profile/?edit=2" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Change Password</a>
                @endif
            </div>
        </form>

        @if($editing == 2)
            <form action="/profile/password" method="POST" class="bg-gray-50 w-1/2">
                @csrf @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm font-semibold">Current Password</label>
                    <input type="password" name="current_password" class="w-full border p-2 rounded" required>
                    <p id="password-msg" class="text-red-500 text-sm errormsg"></p>
                </div>

                <div>
                    <label class="block text-sm font-semibold">New Password</label>
                    <input type="password" name="password" id="setpassword" class="w-full border p-2 rounded" required>
                </div>
                <p id="password-msg" class="text-red-500 text-sm errormsg"></p>
                <div id="strength-bar" class="h-1 w-full bg-gray-200 {{ $errors->has('password') ? '' : 'mb-4' }}">
                    <div id="strength-fill" class="h-full w-0 transition-all"></div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-semibold">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="w-full border p-2 rounded" required>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-[var(--accent,#4c9f2f)] px-5 py-2.5 text-sm font-semibold text-white hover:bg-[var(--accent-dark,#3b7e24)] transition-colors">Confirm</button>
                    <a href="/profile" class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">Cancel</a>
                </div>
            </form>
        @endif
    </div>

    <div class="max-w-2xl mx-auto px-4 pb-10 sm:px-6">
        <div class="border-t pt-6 mt-2">
            <h2 class="text-lg font-semibold mb-3">Quick Links</h2>
            <a href="{{ route('reservation.history') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium hover:bg-gray-50 transition-colors">
                📋 My Reservation History
            </a>
        </div>
    </div>
@endsection