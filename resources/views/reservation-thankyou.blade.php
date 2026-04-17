<!DOCTYPE html>
<html lang="en" id="html">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservation Confirmed - Game Cafe</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Instrument Sans', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Instrument Sans', system-ui, sans-serif;
            transition: background-color 0.4s ease;
        }

        .theme-toggle {
            position: fixed;
            top: 24px;
            right: 24px;
            width: 52px;
            height: 52px;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 2px solid #ddd;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            z-index: 100;
            transition: all 0.3s;
        }

        .dark .theme-toggle {
            background: #1f2937;
            border-color: #4b5563;
            color: white;
        }
    </style>
</head>

<body class="bg-[#F8F9F7] dark:bg-[#0A0C14] min-h-screen flex items-center justify-center p-6">

    <!-- Theme Toggle -->
    <button id="themeToggle" class="theme-toggle">☀️</button>

    <div class="max-w-lg w-full">

        <div class="bg-white dark:bg-[#111827] rounded-3xl shadow-2xl p-8 border border-gray-100 dark:border-gray-700">

            <!-- Header -->
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-900 rounded-full flex items-center justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-11 h-11 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">🎮 Thank You!</h1>
                <p class="text-gray-600 dark:text-gray-300 mt-1">Thank you for reserving your game session!</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Your adventure awaits!</p>
            </div>

            <!-- Reservation Details -->
            <div class="mt-8 bg-gray-50 dark:bg-[#1A2133] rounded-2xl p-7 border border-gray-100 dark:border-gray-700">
                <h2 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-5">RESERVATION DETAILS</h2>
                <div class="grid grid-cols-2 gap-y-5 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Email</span>
                    <span class="font-medium text-right dark:text-white">{{$email}}</span>
                    
                    <span class="text-gray-500 dark:text-gray-400">Date</span>
                    <span class="font-medium text-right dark:text-white">{{ $date }}</span>
                    
                    <span class="text-gray-500 dark:text-gray-400">Time Slot</span>
                    <span class="font-medium text-right dark:text-white">{{ $timeSlot }}</span>
                    
                    <span class="text-gray-500 dark:text-gray-400">Table / Room</span>
                    <span class="font-medium text-right dark:text-white">{{ $table }}</span>
                </div>
            </div>

            <!-- QR Code -->
            <div class="mt-8 flex flex-col items-center">
                <div class="bg-white dark:bg-[#0F172A] p-5 rounded-2xl border border-gray-200 dark:border-gray-700">
                    <div class="w-48 h-48 bg-gray-900 dark:bg-white rounded-2xl flex items-center justify-center p-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-40 h-40 text-white dark:text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">Scan for reservation details</p>
            </div>

            <!-- OK Button -->
            <div class="mt-8 flex justify-center">
                <a href="/" class="px-16 py-4 bg-gray-900 hover:bg-black dark:bg-white dark:text-gray-900 rounded-2xl text-white font-semibold transition">
                    OK
                </a>
            </div>

        </div>
    </div>

    <script>
        const toggleBtn = document.getElementById('themeToggle');
        const html = document.getElementById('html');

        function setTheme(isDark) {
            if (isDark) {
                html.classList.add('dark');
                toggleBtn.textContent = '🌙';
            } else {
                html.classList.remove('dark');
                toggleBtn.textContent = '☀️';
            }
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        // Initialize theme
        const savedTheme = localStorage.getItem('theme');
        setTheme(savedTheme === 'dark');

        // Toggle
        toggleBtn.addEventListener('click', () => {
            const isCurrentlyDark = html.classList.contains('dark');
            setTheme(!isCurrentlyDark);
        });
    </script>
</body>
</html>