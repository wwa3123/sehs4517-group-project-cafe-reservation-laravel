<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Chit-Chat Café')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="app-page min-h-screen @yield('bodyClass')">

    <button class="theme-toggle" id="themeToggleBtn" aria-label="Switch to dark theme">Light</button>

    <nav class="app-nav">
        <div class="app-nav-inner">
            <a href="{{ route('home') }}" class="app-brand">Chit-Chat Café</a>
            <div class="app-nav-links">
                <a href="{{ route('menu') }}"
                   class="app-nav-link {{ request()->routeIs('menu') ? 'active' : '' }}">
                    Menu
                </a>
                <a href="{{ route('reservations.index') }}"
                   class="app-nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                    Reservations
                </a>
                <a href="{{ route('events.index') }}"
                   class="app-nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                    Events
                </a>
                @auth
                <a href="{{ route('reservation.history') }}"
                   class="app-nav-link {{ request()->routeIs('reservation.history') ? 'active' : '' }}">
                    My Visits
                </a>
                <a href="{{ route('profile') }}"
                   class="app-nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                    Profile
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="app-nav-link app-nav-logout">Logout</button>
                </form>
                @else
                <a href="{{ route('login') }}" class="app-nav-link {{ request()->routeIs('login*') ? 'active' : '' }}">Sign in</a>
                <a href="{{ route('register') }}" class="app-nav-link app-nav-cta {{ request()->routeIs('register*') ? 'active' : '' }}">Create account</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="app-content">
        @yield('content')
    </div>

    <script>
    (function () {
        var btn  = document.getElementById('themeToggleBtn');
        var body = document.body;
        var stored = localStorage.getItem('theme') || 'light';
        if (stored === 'dark') {
            body.classList.add('dark');
            btn.innerHTML = 'Dark';
            btn.setAttribute('aria-label', 'Switch to light theme');
        } else {
            btn.innerHTML = 'Light';
        }
        btn.addEventListener('click', function () {
            if (body.classList.contains('dark')) {
                body.classList.remove('dark');
                btn.innerHTML = 'Light';
                btn.setAttribute('aria-label', 'Switch to dark theme');
                localStorage.setItem('theme', 'light');
            } else {
                body.classList.add('dark');
                btn.innerHTML = 'Dark';
                btn.setAttribute('aria-label', 'Switch to light theme');
                localStorage.setItem('theme', 'dark');
            }
        });
    })();
    </script>

    @stack('scripts')
</body>
</html>
