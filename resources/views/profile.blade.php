<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/validation.js'])

    <title>Profile - Boardgame Café</title>
</head>

<body class="bg-gray-100 text-gray-900">
    <div class="max-w-2xl mx-auto p-8 bg-white shadow rounded-lg mt-10">
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
                    <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded">Update Profile</button>
                    <a href="/profile" class="bg-gray-400 text-white px-6 py-2 rounded">Cancel</a>
                @elseif($editing == 2)
                @else
                    <a href="/profile?edit=1" class="bg-blue-600 text-white px-6 py-2 rounded">Edit Profile</a>
                    <a href="/profile/?edit=2" class="bg-gray-800 text-white px-6 py-2 rounded">Change Password</a>
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
                    <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded">Confirm</button>
                    <a href="/profile" class="bg-gray-400 text-white px-6 py-2 rounded">Cancel</a>
                </div>
            </form>
        @endif
    </div>
</body>

</html>