<!-- resources/views/livewire/personal-chat.blade.php -->
<div class="flex h-screen">
    <!-- Sidebar -->
    <div class="w-1/4 bg-white border-r border-gray-300">
        <!-- Contact List -->
        <div class="overflow-y-auto h-screen p-3">
           <!-- Contact Item -->
           <a href="#chat" class="flex items-center mb-4 p-2 rounded-md hover:bg-gray-100 cursor-pointer">
            <div class="relative w-12 h-12 bg-gray-300 rounded-full mr-3">
                <!-- Online Indicator -->
                <span class="absolute bottom-0 right-0 block w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                <img src="https://placehold.co/200x/ffa8e4/ffffff.svg?text=ʕ•́ᴥ•̀ʔ&font=Lato" alt="User Avatar" class="w-12 h-12 rounded-full">
            </div>
            <div class="flex-1">
                <h2 class="text-lg font-semibold">Alice USER</h2>
                <p class="text-gray-600">Hoorayy!! USER MESSAGE</p>
            </div>
        </a>
        <!-- Add more contact items here -->
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="flex-1 flex flex-col">
        <!-- Chat Header -->
        <header class="bg-white p-4 border-b border-gray-300 text-gray-700">
            <h1 class="text-2xl font-semibold">Alice</h1>
        </header>

        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto p-4">
            <!-- Incoming Message -->
            <div class="flex mb-4">
                <div class="w-9 h-9 rounded-full flex items-center justify-center mr-2">
                    <img src="https://placehold.co/200x/ffa8e4/ffffff.svg?text=ʕ•́ᴥ•̀ʔ&font=Lato" alt="User Avatar" class="w-8 h-8 rounded-full">
                </div>
                <div class="flex max-w-96 bg-white rounded-lg p-3">
                    <p class="text-gray-700">Hey Bob, how's it going?</p>
                </div>
            </div>

            <!-- Outgoing Message -->
            <div class="flex justify-end mb-4">
                <div class="flex max-w-96 bg-indigo-500 text-white rounded-lg p-3">
                    <p>Hi Alice! I'm good, just finished a great book. How about you?</p>
                </div>
                <div class="w-9 h-9 rounded-full flex items-center justify-center ml-2">
                    <img src="https://placehold.co/200x/b7a8ff/ffffff.svg?text=ʕ•́ᴥ•̀ʔ&font=Lato" alt="My Avatar" class="w-8 h-8 rounded-full">
                </div>
            </div>
        </div>

        <!-- Chat Input -->
        <footer class="bg-white border-t border-gray-300 p-4">
            <div class="flex items-center">
                <input type="text" wire:model="newMessage" wire:keydown.enter="handleMessageSubmission" placeholder="Type a message..." class="w-full p-2 rounded-md border border-gray-400 focus:outline-none focus:border-blue-500">
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md ml-2" wire:click="handleMessageSubmission">Send</button>
            </div>
        </footer>
    </div>
</div>
