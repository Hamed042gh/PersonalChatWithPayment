<div wire:poll.20s>
    @php
        use Carbon\Carbon;
    @endphp
    <div class="w-full bg-gray-200 rounded-full h-2.5">
        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($user->messages_count / 10) * 100 }}%"></div>
    </div>
    <span class="text-sm text-gray-500">You have sent {{ Auth::user()->messages_count }} messages</span>
    
    <div class="flex h-screen">
        <!-- Sidebar (User List) -->
        <div class="w-1/4 bg-white border-r border-gray-300">
            <!-- User List -->
            <div class="overflow-y-auto h-screen p-3" wire:poll.5s>

                @foreach ($users as $user)
            
                    <!-- User Item -->
                    <a wire:click="chooseUser({{ $user->id }})"
                        class="flex items-center mb-4 p-2 rounded-md hover:bg-gray-100 cursor-pointer">
                        <div class="relative w-12 h-12 bg-gray-300 rounded-full mr-3">
                            <span
                                class="absolute bottom-0 right-0 block w-5 h-5 {{ $user->isOnline() ? 'bg-green-500' : 'bg-gray-300' }} border-4 border-white rounded-full"></span>

                            <div
                                class="w-8 h-8 {{ $user->isOnline() ? 'bg-green-400 text-white' : 'bg-gray-400 text-gray-800' }} rounded-full flex items-center justify-center">
                                <span class="font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                        </div>

                        <div class="flex-1">
                            <h2 class="text-lg font-semibold">{{ $user->name }}</h2>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- Main Chat Panel -->
        <div class="flex-1 flex flex-col">
            @if ($selectedUser)
                <!-- Chat Header -->
                <header class="bg-white p-4 border-b border-gray-300 text-gray-700">
                    <h1 class="text-2xl font-semibold text-center">{{ $selectedUser->name }}</h1>

                </header>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-4"
                    style="display: flex; flex-direction: column-reverse; scroll-behavior: smooth;">
                    @foreach ($messages as $message)
                        <div
                            class="flex mb-4 {{ $message['sender_id'] == auth()->user()->id ? 'justify-start' : 'justify-end' }}">
                            @if ($message['sender_id'] == auth()->user()->id)
                                <div
                                    class="flex max-w-xs bg-white text-gray-800 rounded-lg p-3 shadow-md border border-gray-300">
                                    <div class="flex items-center mb-1">
                                        <!-- اگر تصویر کاربر را دارید، می‌توانید آن را اینجا قرار دهید -->
                                    </div>
                                    <p>{{ $message['content'] }}</p>
                                </div>
                                <div class="text-xs text-gray-500 mt-1 ml-2">
                                    {{ Carbon::parse($message['created_at'])->diffForHumans() }}
                                </div>
                            @else
                                <!-- Message Received -->
                                <div class="flex max-w-xs bg-indigo-500 text-white rounded-lg p-3 shadow-md">
                                    <p>{{ $message['content'] }}</p>
                                </div>
                                <div class="text-xs text-gray-300 mt-1 ml-2">
                                    {{ Carbon::parse($message['created_at'])->diffForHumans() }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Chat Input -->
                <footer class="bg-white border-t border-gray-300 p-4">
                    <div class="flex items-center">
                        <input type="text" wire:model.defer="newMessage" wire:keydown.enter="handleMessageSubmission"
                            placeholder="Type your message..."
                            class="w-full p-2 rounded-md border border-gray-400 focus:outline-none focus:border-blue-500">
                        <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md ml-2"
                            wire:click="handleMessageSubmission">Send</button>
                    </div>
                </footer>
            @else
                <div class="flex flex-col justify-center items-center h-full text-center">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12.2A9 9 0 1112.8 3a9 9 0 018.2 9z"></path>
                    </svg>
                    <p class="text-xl text-gray-500 font-medium">No conversation selected</p>
                    <p class="text-sm text-gray-400 mt-1">Please select a user to start chatting.</p>
                </div>
            @endif
        </div>
    </div>
</div>
