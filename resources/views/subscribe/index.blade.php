<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
@if (session()->has('message'))
    <div class="bg-yellow-500 text-white p-4 rounded mb-4">
        {{ session('message') }}
    </div>
@endif
<div class="max-w-lg mx-auto mt-10">
    <h1 class="text-3xl font-bold mb-4">Subscribe to Continue Chatting</h1>
    <p class="text-gray-700 mb-6">Upgrade your plan to enjoy unlimited chats.</p>

    <a href="/purchase"
        class="text-white bg-gradient-to-r from-green-400 to-blue-500 hover:from-green-500 hover:to-blue-600 font-bold py-3 px-6 rounded-full shadow-lg transition-all duration-300 ease-in-out">
        Subscribe Now
    </a>
    <a href="/dashboard"
        class="text-white bg-gradient-to-r from-blue-400 to-green-500 hover:from-green-500 hover:to-blue-600 font-bold py-3 px-6 rounded-full shadow-lg transition-all duration-300 ease-in-out">
        Home
    </a>
</div>
