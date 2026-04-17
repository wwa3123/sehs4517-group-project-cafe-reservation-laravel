<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reservation Confirmed - Game Cafe</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <script>
        tailwind.config = {
            content: [],
            darkMode: 'class',   // Enables dark: prefix
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
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Instrument Sans', system-ui, sans-serif;
            transition: background-color 0.3s ease, color 0.2s ease;
        }

        /* Theme Toggle Button */
        .theme-toggle {
            position: fixed;
            top: 24px;
            right: 24px;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            background-color: rgb(255 255 255);
            border: 1px solid #e5e7eb;
            border-radius: 9999px;
            cursor: pointer;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.05);
            transition: all 0.25s ease;
            z-index: 50;
        }

        body.dark .theme-toggle {
            background-color: rgb(31 41 55);
            border-color: rgb(55 65 81);
            color: white;
        }

        .theme-toggle:hover {
            transform: scale(1.08);
        }
    </style>
</head>

<body class="bg-[#FDFDFC] dark:bg-gray-950 min-h-screen flex items-center justify-center p-6 font-sans">

    <!-- Theme Toggle -->
    <button class="theme-toggle" id="themeToggleBtn" aria-label="Toggle theme">
        ☀️
    </button>

    <div class="max-w-lg w-full">

        <div class="bg-white dark:bg-gray-900 rounded-3xl shadow-2xl p-8 border border-gray-200 dark:border-gray-800 flex flex-col gap-8">

            <!-- Success Header -->
            <div class="flex flex-col items-center text-center">
                <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-900/50 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-11 h-11 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                    🎮 Thank You!
                </h1>
                <p class="text-base font-medium text-gray-700 dark:text-gray-300 mt-1">Thank you for reserving your game session!</p>
                <p class="text-sm text-gray-500 dark:text-gray-400">Your adventure awaits!</p>
            </div>

            <!-- Reservation Details -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-7 border border-gray-200 dark:border-gray-700">
                <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-5">Reservation Details</h2>
                <div class="grid grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Email</span>
                    <span class="font-medium text-right text-gray-900 dark:text-white">{{$email}}</span>
                    
                    <span class="text-gray-500 dark:text-gray-400">Date</span>
                    <span class="font-medium text-right text-gray-900 dark:text-white">{{ $date }}</span>
                    
                    <span class="text-gray-500 dark:text-gray-400">Time Slot</span>
                    <span class="font-medium text-right text-gray-900 dark:text-white">{{ $timeSlot }}</span>
                    
                    <span class="text-gray-500 dark:text-gray-400">Table / Room</span>
                    <span class="font-medium text-right text-gray-900 dark:text-white">{{ $table }}</span>
                </div>
            </div>

            <!-- QR Code -->
            <div class="flex flex-col items-center gap-4">
                <div class="bg-white dark:bg-gray-900 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="w-44 h-44 bg-gray-900 dark:bg-white rounded-2xl flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-36 h-36 text-white dark:text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 text-center">Scan for reservation details</p>
            </div>

            <!-- Popular Games -->
            @if(!empty($gameSuggestions))
            <div class="bg-gray-50 dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-5 text-center">
                    Popular Games Available
                </h3>
                <div class="flex flex-wrap gap-3 justify-center">
                    @foreach($gameSuggestions as $game)
                        <span class="px-5 py-2.5 bg-white dark:bg-gray-700 text-sm font-medium rounded-full border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200">
                            {{ $game }}
                        </span>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- OK Button -->
            <div class="flex justify-center pt-4">
                <a href="/" 
                   class="px-14 py-4 bg-gray-900 dark:bg-white dark:text-gray-900 text-white font-semibold rounded-2xl hover:bg-black dark:hover:bg-gray-100 transition-all text-base shadow-lg">
                    OK
                </a>
            </div>

        </div>
    </div>

    <script>
        // ==================== Theme Toggle (Same logic as your login page) ====================
        const themeToggleBtn = document.getElementById('themeToggleBtn');
        const body = document.body;

        const getStoredTheme = () => localStorage.getItem('theme');
        const setStoredTheme = (theme) => localStorage.setItem('theme', theme);

        const applyTheme = (theme) => {
            if (theme === 'dark') {
                body.classList.add('dark');
                themeToggleBtn.innerHTML = '🌙';
            } else {
                body.classList.remove('dark');
                themeToggleBtn.innerHTML = '☀️';
            }
        };

        const initTheme = () => {
            const storedTheme = getStoredTheme();
            
            if (storedTheme === 'dark') {
                applyTheme('dark');
            } else if (storedTheme === 'light') {
                applyTheme('light');
            } else {
                // Default to light (you can change to 'dark' if preferred)
                applyTheme('light');
                setStoredTheme('light');
            }
        };

        const toggleTheme = () => {
            const isDark = body.classList.contains('dark');
            if (isDark) {
                applyTheme('light');
                setStoredTheme('light');
            } else {
                applyTheme('dark');
                setStoredTheme('dark');
            }
        };

        themeToggleBtn.addEventListener('click', toggleTheme);
        initTheme();
    </script>
</body>
</html>