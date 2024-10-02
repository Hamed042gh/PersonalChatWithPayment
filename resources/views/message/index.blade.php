<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        @vite('resources/css/app.css')

        @if (!Request::routeIs('login', 'register'))
            @livewireStyles
        @endif
    </head>

    <body class="bg-gray-100">

        @auth
            <script>
                window.userId = {{ auth()->user()->id }};
            </script>
        @endauth
        @if (session('message'))
            <div class="alert alert-warning">
                {{ session('message') }}
            </div>
        @endif

        @auth
            <div class="p-4 flex space-x-4">
                <a href="{{ route('dashboard') }}"
                    class="text-white bg-gradient-to-r from-purple-400 to-indigo-500 hover:from-purple-500 hover:to-indigo-600 font-bold py-3 px-6 rounded-full shadow-lg transition-all duration-300 ease-in-out border-b-4 border-transparent hover:border-indigo-600">
                    Home
                </a>

                <a href="/subscribe"
                    class="text-white bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-500 hover:to-blue-600 font-bold py-3 px-6 rounded-full shadow-lg transition-all duration-300 ease-in-out border-b-4 border-transparent hover:border-blue-600">
                    Subscribe
                </a>
            </div>
        

        @endauth

        @if (!Request::routeIs('login', 'register'))
            <livewire:personal-chat />
        @endif

        @vite('resources/js/app.js')
        @if (!Request::routeIs('login', 'register'))
            @livewireScripts
        @endif
    </body>

</html>
