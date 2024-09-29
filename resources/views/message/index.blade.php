<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        @vite('resources/css/app.css')

        @if (!Request::routeIs('login', 'register'))
            <!-- بررسی اینکه صفحه لاگین یا ثبت‌نام نباشد -->
            @livewireStyles
        @endif
    </head>

    <body class="bg-gray-100">
        @auth
            <script>
                window.userId = {{ auth()->user()->id }};
            </script>
        @endauth

        @auth
            <div class="p-4">
                <a href="{{ route('dashboard') }}"
                    class="text-white bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">
                    Home
                </a>
            </div>
        @endauth

        @if (!Request::routeIs('login', 'register'))
            <livewire:personal-chat />
        @endif

        @vite('resources/js/app.js')
        @if (!Request::routeIs('login', 'register'))
            <!-- بررسی اینکه صفحه لاگین یا ثبت‌نام نباشد -->
            @livewireScripts
        @endif
    </body>

</html>
