<x-app-layout>
    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-500 text-white p-4 rounded">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="p-4 bg-gray-100">

        <a href="{{ route('chat') }}"
            class="text-white bg-blue-500 hover:bg-blue-700 font-bold py-2 px-4 rounded transition duration-300 ease-in-out shadow-md">
            Start Chat
        </a>

        @if (Auth::user()->messages_count >= 3)
            <a href="/subscribe"
                class="text-white bg-green-500 hover:bg-green-700 font-bold py-2 px-4 rounded transition duration-300 ease-in-out shadow-md">
                Subscribe
            </a>
        @endif

    </div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
