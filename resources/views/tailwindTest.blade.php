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
            <h1 class="text-2xl font-bold text-indigo-600">Boardgame Café</h1>

            <div class="space-x-6 text-lg">
                <a href="#" class="hover:text-indigo-600">Home</a>
                <a href="#" class="hover:text-indigo-600">Menu</a>
                <a href="#" class="hover:text-indigo-600">Reservations</a>
                <a href="#" class="hover:text-indigo-600">Contact</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="max-w-7xl mx-auto px-6 py-20">
        <div class="grid md:grid-cols-2 gap-10 items-center">

            <div>
                <h2 class="text-4xl font-extrabold mb-6">
                    Play. Relax. Enjoy.
                </h2>

                <p class="text-lg text-gray-700 mb-8">
                    Welcome to the ultimate boardgame café experience.  
                    Reserve your table, enjoy great drinks, and have fun with friends.
                </p>

                <a href="#"
                   class="inline-block bg-indigo-600 text-white px-6 py-3 rounded-lg text-lg font-semibold hover:bg-indigo-700 transition">
                    Book a Table
                </a>
            </div>

            <div>
                <div class="bg-white shadow-lg rounded-xl p-6">
                    <img src=""
                         class="rounded-lg shadow-md"
                         alt="Boardgame café">
                </div>
            </div>

        </div>
    </section>

</body>
</html>