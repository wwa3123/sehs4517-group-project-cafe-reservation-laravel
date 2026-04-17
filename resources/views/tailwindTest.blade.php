<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Boardgame Café</title>
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