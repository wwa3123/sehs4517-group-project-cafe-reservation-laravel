<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/validation.js'])

    <title>Registration - Boardgame Café</title>
</head>

<body class="bg-gray-100 text-gray-900">
    
    <!-- Navbar 
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-indigo-600">Boardgame Café</h1>

            <div class="space-x-6 text-lg">
                <a href="#" class="hover:text-indigo-600">Home</a>
                <a href="#" class="hover:text-indigo-600">Menu</a>
                <a href="#" class="hover:text-indigo-600">Reservations</a>
                <a href="#" class="hover:text-indigo-600">Contact</a>
            </div>
        </div>
    </nav> -->

    <form action="/register" method="POST" id="reg" class="max-w-md mx-auto p-6 bg-white shadow-md">
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
</body>

</html>