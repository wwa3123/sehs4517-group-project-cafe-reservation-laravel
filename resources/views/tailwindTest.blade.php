<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Boardgame Café</title>
    <style>
            /* ---------- RESET ---------- */
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
    
            /* light mode */
            :root {
                --bg-page: #f0f7ee;
                --card-bg: #ffffff;
                --text-primary: #1e2a1c;
                --text-secondary: #3a5a34;
                --text-muted: #6b7c68;
                --border-light: #ddebe0;
                --input-bg: #ffffff;
                --input-border: #cbdcd0;
                --input-focus: #6fbf4c;
                --accent-green: #4c9f2f;
                --accent-green-dark: #3b7e24;
                --accent-hover: #e9f5e3;
                --link-color: #4c9f2f;
                --shadow-sm: 0 12px 30px rgba(0, 0, 0, 0.05), 0 4px 8px rgba(0, 0, 0, 0.02);
                --transition: all 0.25s ease;
            }
    
            /* dark mode */
            body.dark {
                --bg-page: #121212;
                --card-bg: #1e2a1c;
                --text-primary: #eef5ea;
                --text-secondary: #cfe3c7;
                --text-muted: #a1b89a;
                --border-light: #2c3e28;
                --input-bg: #2a3a26;
                --input-border: #415e3a;
                --input-focus: #7ed957;
                --accent-green: #7ed957;
                --accent-green-dark: #5fb03e;
                --accent-hover: #2c3e28;
                --link-color: #8cd96c;
                --shadow-sm: 0 12px 30px rgba(0, 0, 0, 0.4);
            }
    
            body {
                background-color: var(--bg-page);
                font-family: 'Segoe UI', 'Poppins', system-ui, -apple-system, 'Inter', 'Noto Sans', sans-serif;
                transition: background-color 0.3s ease, color 0.2s ease;
                color: var(--text-primary);
                line-height: 1.5;
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px 20px;
                position: relative;
            }
    
            /* dark mode button */
            .theme-toggle {
                position: fixed;
                top: 24px;
                right: 24px;
                background: var(--card-bg);
                border: 1px solid var(--border-light);
                border-radius: 60px;
                width: 48px;
                height: 48px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 1.6rem;
                backdrop-filter: blur(4px);
                box-shadow: var(--shadow-sm);
                transition: var(--transition);
                z-index: 999;
                background-color: var(--card-bg);
                color: var(--text-primary);
            }
    
            .theme-toggle:hover {
                transform: scale(1.05);
                background-color: var(--accent-hover);
                border-color: var(--accent-green);
            }
        </style>
</head>

<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-indigo-600">Chit Chat Cafe</h1>

            <div class="space-x-6 text-lg">
                <a href="tailwindtest.blade.php" class="hover:text-indigo-600">Home</a>
                <a href="#" class="hover:text-indigo-600">Library</a>
                <a href="#" class="hover:text-indigo-600">Snacks & Drinks</a>
                <a href="#" class="hover:text-indigo-600">Login + Profile</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="grid md:grid-cols-2 gap-10 items-center">

            <div>
                <h1 class="text-4xl font-extrabold mb-6">
                    Chit Chat Cafe
                </h1>
                
                <h2 class="text-4xl font-extrabold mb-6">
                    Cozy Chit | Easy Chat
                </h2>
                
                <h3 class="text-4xl font-extrabold mb-6">
                    Who we are:
                </h3>
                <p class="text-lg text-gray-700 mb-8">
                    Welcome to Chit Chat Cafe, you can enjoy over 100 board & card games in here.
                    This cafe have 3 types of table: Standard, Gaming and VIP, provide all the customers
                    the most comfort, most motive, and the most premium game experiences.
                    In the late of July, we will have the "Strategy Game Tournament", a competition for maximum of 16 
                    participants featuring strategic board games, a CHAMPION CUP will be awarded to the winner.
                    Also, we have the "Family Game Night" on every Saturday, come with your family and WIN the BIG PRIZE.
                    Hope you have a wonderful and cozy game experience in our Chit Chat Cafe.
                </p>

                <h3 class="text-4xl font-extrabold mb-6">
                    Do you know:
                </h3>
                <p class="text-lg text-gray-700 mb-8">
                    This cafe has been in business for over a hundred years. Back to the 1922, the old owner Victoria Shek 
                    was held this cafe, and she create the game Catan and invite everyone to play when they visit the cafe,
                    here is the first boardgame cafe all over the world. Here is the little secret, during the World War II
                    (1942 - 1945), this cafe was once the headquarters of the Anti-Japanese Guerrillas, and was hailed as 
                    the "Sanctuary for the Allies".
                </p>

                <a href="#"
                   class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-indigo-700 transition">
                    Book a Table
                </a>
            </div>

            <div>
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <img src="cafe_logo.png"
                         class="rounded-lg shadow-md"
                         alt="Chit Chat Cafe Logo">
                </div>
            </div>

        </div>
    </section>

</body>
</html>