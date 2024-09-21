<head>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    @livewireStyles
</head>

<body>
    @auth
    <div class="p-4 bg-gray-100">
        <a href="{{ route('dashboard') }}" class="text-white bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded">
            Home
        </a>

    </div>
    @endauth


    <livewire:personal-chat />

    @livewireScripts
</body>
