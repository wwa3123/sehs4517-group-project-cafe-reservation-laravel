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
<body class="app-page min-h-screen">

    {{-- Dark mode toggle --}}
    <button class="theme-toggle" id="themeToggleBtn" aria-label="Toggle theme">🌞</button>

    {{-- Navigation --}}
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
                <a href="{{ route('profile') }}"
                   class="app-nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                    Profile
                </a>
                <a href="{{ route('logout') }}" class="app-nav-link app-nav-logout">Logout</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Main content area --}}
    <div class="app-content">
        @yield('content')
    </div>

    {{-- Dark mode init script --}}
    <script>
    (function () {
        var btn  = document.getElementById('themeToggleBtn');
        var body = document.body;
        var stored = localStorage.getItem('theme') || 'light';
        if (stored === 'dark') {
            body.classList.add('dark');
            btn.innerHTML = '🌙';
        } else {
            btn.innerHTML = '🌞';
        }
        btn.addEventListener('click', function () {
            if (body.classList.contains('dark')) {
                body.classList.remove('dark');
                btn.innerHTML = '🌞';
                localStorage.setItem('theme', 'light');
            } else {
                body.classList.add('dark');
                btn.innerHTML = '🌙';
                localStorage.setItem('theme', 'dark');
            }
        });
    })();
    </script>

    @stack('scripts')
</body>
</html>
