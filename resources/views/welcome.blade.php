<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles (Tailwind) -->
        <script src="https://cdn.tailwindcss.com"></script>
        
    </head>

    <body class="bg-gray-100 dark:bg-gray-900 min-h-screen relative">
        <div class="absolute top-0 right-0 p-4">
            @if (Route::has('login'))
                <nav class="flex space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-4 py-2 text-white bg-indigo-600 rounded-lg shadow-lg transition transform hover:bg-indigo-500 hover:scale-105 focus:ring focus:ring-indigo-300">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 text-white bg-blue-500 rounded-lg shadow-lg transition transform hover:bg-blue-400 hover:scale-105 focus:ring focus:ring-blue-300">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-4 py-2 text-white bg-green-500 rounded-lg shadow-lg transition transform hover:bg-green-400 hover:scale-105 focus:ring focus:ring-green-300">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>

        <div class="container mx-auto p-4 flex justify-center items-center min-h-screen">

            <div class="flex flex-col items-center justify-center min-h-screen relative text-center">
                <div class="absolute top-16 animate-bounce">
                   
                        <img src="{{ asset('images/chat7403.png') }}" alt="Chat Logo"
                            class="w-32 h-32 mb-6 rounded-full shadow-lg">
                    

                </div>
                <h1
                    class="text-5xl font-extrabold text-gray-900 dark:text-white mt-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent">
                    Welcome To Personal Chat
                </h1>
                <a href="" class="mt-8 px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-lg transition-transform transform hover:bg-indigo-500 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    Start Chat
                </a>
            </div>

        </div>

    </body>

</html>
