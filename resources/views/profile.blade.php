@extends('layouts.app')
@section('title', 'Profile')
@push('head')
    @vite('resources/js/validation.js')
@endpush
@section('content')
    <div class="max-w-2xl w-full rounded-3xl border shadow-xl p-10 mx-auto my-8 transition-all sm:p-7" style="background-color: var(--card-bg); border-color: var(--border);">
        @if (session()->has('success'))
            <div class="p-3.5 rounded-2xl mb-6 font-semibold" style="background-color: var(--accent-tint); border: 1px solid var(--accent); color: var(--text-secondary);">
                ✓ {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3.5 rounded-2xl mb-6 bg-red-200 border border-red-500">
                @foreach ($errors->all() as $error)
                    <p class="text-red-900 text-sm my-1.5">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <h2 class="text-4xl font-bold text-transparent bg-clip-text mb-7 pb-4 border-b -tracking-wide" style="background-image: linear-gradient(135deg, var(--accent, #4c9f2f) 0%, #7ac74f 100%); border-color: var(--accent-tint);">Your Profile</h2>

        <form action="/profile" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-4.5 mb-4 md:grid-cols-2">
                <div>
                    <label for="first_name" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">First Name</label>
                    @if($editing == 1)
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" required class="w-full px-3.5 py-3 border-2 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
                    @else
                        <div class="px-3.5 py-3 rounded-2xl text-sm" style="background-color: var(--accent-tint); color: var(--text-primary);">{{ $user->first_name }}</div>
                    @endif
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">Last Name</label>
                    @if($editing == 1)
                        <input type="text" id="last_name" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="w-full px-3.5 py-3 border-2 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
                    @else
                        <div class="px-3.5 py-3 rounded-2xl text-sm" style="background-color: var(--accent-tint); color: var(--text-primary);">{{ $user->last_name }}</div>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">Email Address</label>
                @if($editing == 1)
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-3.5 py-3 border-2 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
                @else
                    <div class="px-3.5 py-3 rounded-2xl text-sm" style="background-color: var(--accent-tint); color: var(--text-primary);">{{ $user->email }}</div>
                @endif
            </div>

            <div class="mb-4">
                <label for="phone" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">Phone</label>
                @if($editing == 1)
                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required class="w-full px-3.5 py-3 border-2 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
                @else
                    <div class="px-3.5 py-3 rounded-2xl text-sm" style="background-color: var(--accent-tint); color: var(--text-primary);">{{ $user->phone }}</div>
                @endif
            </div>

            <div class="mb-7">
                <label for="address" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">Address</label>
                @if($editing == 1)
                    <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}" required class="w-full px-3.5 py-3 border-2 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
                @else
                    <div class="px-3.5 py-3 rounded-2xl text-sm" style="background-color: var(--accent-tint); color: var(--text-primary);">{{ $user->address ?? 'Not provided' }}</div>
                @endif
            </div>

            <div class="flex gap-3 sm:flex-col-reverse">
                @if($editing == 1)
                    <a href="/profile" class="flex-1 py-3 px-4 text-sm font-bold rounded-2xl transition-all tracking-wide text-center" style="background-color: var(--border); color: var(--text-secondary);" onmouseover="this.style.backgroundColor='#cde2d5'" onmouseout="this.style.backgroundColor='var(--border)'">Cancel</a>
                    <button type="submit" class="flex-1 py-3 px-4 text-sm font-bold rounded-2xl text-white transition-all tracking-wide" style="background-color: var(--accent);" onmouseover="this.style.backgroundColor='var(--accent-dark)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(76, 159, 47, 0.3)'" onmouseout="this.style.backgroundColor='var(--accent)'; this.style.transform='translateY(0)'; this.style.boxShadow=''">Update Profile</button>
                @elseif($editing == 2)
                @else
                    <a href="/profile?edit=1" class="w-full py-3 px-4 text-sm font-bold rounded-2xl text-white transition-all tracking-wide text-center" style="background-color: var(--accent);" onmouseover="this.style.backgroundColor='var(--accent-dark)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(76, 159, 47, 0.3)'" onmouseout="this.style.backgroundColor='var(--accent)'; this.style.transform='translateY(0)'; this.style.boxShadow=''">Edit Profile</a>
                @endif
            </div>

            @if($editing != 1 && $editing != 2)
                <div class="flex gap-3 mt-3 sm:flex-col-reverse">
                    <a href="/profile/?edit=2" class="w-full py-3 px-4 text-sm font-bold rounded-2xl transition-all tracking-wide text-center" style="background-color: var(--border); color: var(--text-secondary);" onmouseover="this.style.backgroundColor='#cde2d5'" onmouseout="this.style.backgroundColor='var(--border)'">Change Password</a>
                </div>
            @endif
        </form>

        @if($editing == 2)
            <div class="rounded-3xl p-6 mt-6 sm:p-4.5" style="background-color: var(--accent-tint);">
                <h3 class="text-lg font-bold mb-4.5" style="color: var(--text-secondary);">Change Password</h3>
                <form action="/profile/password" method="POST">
                    @csrf @method('PUT')

                    <div class="mb-4">
                        <label for="current_password" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">Current Password</label>
                        <input type="password" id="current_password" name="current_password" required class="w-full px-3.5 py-3 border-2 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
                        <p id="password-msg" class="text-red-500 text-xs mt-1.5"></p>
                    </div>

                    <div class="mb-4">
                        <label for="setpassword" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">New Password</label>
                        <input type="password" id="setpassword" name="password" required class="w-full px-3.5 py-3 border-2 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
                        <div id="strength-bar" class="h-1 w-full mb-4 hidden">
                            <div id="strength-fill" class="h-full w-0 bg-red-500 transition-all"></div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-semibold mb-2 tracking-wide" style="color: var(--text-secondary);">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-3.5 py-3 border-2 rounded-2xl transition-all focus:outline-none" style="background-color: var(--card-bg); color: var(--text-primary); border-color: #d0d5cf;" onfocus="this.style.borderColor='var(--accent)'; this.style.boxShadow='0 0 0 3px var(--accent-tint)'" onblur="this.style.borderColor='#d0d5cf'; this.style.boxShadow=''">
                    </div>

                    <div class="flex gap-3 sm:flex-col-reverse">
                        <a href="/profile" class="flex-1 py-3 px-4 text-sm font-bold rounded-2xl transition-all tracking-wide text-center" style="background-color: var(--border); color: var(--text-secondary);" onmouseover="this.style.backgroundColor='#cde2d5'" onmouseout="this.style.backgroundColor='var(--border)'">Cancel</a>
                        <button type="submit" class="flex-1 py-3 px-4 text-sm font-bold rounded-2xl text-white transition-all tracking-wide" style="background-color: var(--accent);" onmouseover="this.style.backgroundColor='var(--accent-dark)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(76, 159, 47, 0.3)'" onmouseout="this.style.backgroundColor='var(--accent)'; this.style.transform='translateY(0)'; this.style.boxShadow=''">Confirm Password Change</button>
                    </div>
                </form>
            </div>
        @endif
    </div>

    <div class="max-w-4xl w-full mx-auto mb-8 px-4">
        <h3 class="text-sm font-bold mb-3 tracking-widest uppercase" style="color: var(--text-secondary);">Quick Links</h3>
        <a href="{{ route('reservation.history') }}" class="inline-flex items-center gap-2 px-4.5 py-2.5 border-2 rounded-2xl text-sm font-semibold no-underline transition-all" style="border-color: var(--border); color: var(--accent);" onmouseover="this.style.backgroundColor='var(--accent-tint)'; this.style.borderColor='var(--accent)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.backgroundColor='transparent'; this.style.borderColor='var(--border)'; this.style.transform='translateY(0)'">📋 My Reservation History</a>
    </div>
@endsection