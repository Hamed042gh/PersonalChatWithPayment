<div>

    <div class="flex h-screen">
        <!-- نوار کناری (لیست کاربران) -->
        <div class="w-1/4 bg-white border-r border-gray-300">
            <!-- لیست کاربران -->
            <div class="overflow-y-auto h-screen p-3">
                @foreach ($users as $user)
                    <!-- آیتم کاربر -->
                    <a wire:click="chooseUser({{ $user->id }})"
                        class="flex items-center mb-4 p-2 rounded-md hover:bg-gray-100 cursor-pointer">

                        <div class="relative w-12 h-12 bg-gray-300 rounded-full mr-3">
                            <span
                                class="absolute bottom-0 right-0 block w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                            <img src="https://placehold.co/200x/ffa8e4/ffffff.svg?text=ʕ•́ᴥ•̀ʔ&font=Lato"
                                alt="User Avatar" class="w-12 h-12 rounded-full">
                        </div>
                        <div class="flex-1">
                            <h2 class="text-lg font-semibold">{{ $user->name }}</h2>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <!-- پنل چت اصلی -->
        <div class="flex-1 flex flex-col">
            @if ($selectedUser)
                <!-- سرصفحه چت -->
                <header class="bg-white p-4 border-b border-gray-300 text-gray-700">
                    <h1 class="text-2xl font-semibold">{{ $selectedUser->name }}</h1>
                </header>

                <!-- پیام‌ها -->
                <div class="flex-1 overflow-y-auto p-4">
                    @foreach ($messages as $message)
                        <div class="flex mb-4 {{ $message['sender_id'] == $user->id ? 'justify-end' : '' }}">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center mr-2">
                                <img src="https://placehold.co/200x/ffa8e4/ffffff.svg?text=ʕ•́ᴥ•̀ʔ&font=Lato"
                                    alt="User Avatar" class="w-8 h-8 rounded-full">
                            </div>
                            <div
                                class="flex max-w-96 {{ $message['sender_id'] == $user->id ? 'bg-indigo-500 text-white' : 'bg-white text-gray-700' }} rounded-lg p-3">
                                <p>{{ $message['content'] }}</p>
                            </div>
                        </div>
                    @endforeach

                </div>

                <!-- ورودی چت -->
                <footer class="bg-white border-t border-gray-300 p-4">
                    <div class="flex items-center">
                        <input type="text" wire:model="newMessage" wire:keydown.enter="handleMessageSubmission"
                            placeholder="پیام خود را تایپ کنید..."
                            class="w-full p-2 rounded-md border border-gray-400 focus:outline-none focus:border-blue-500">
                        <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded-md ml-2"
                            wire:click="handleMessageSubmission">ارسال</button>
                    </div>
                </footer>
            @else
                <p class="text-center m-4">لطفاً یک کاربر را برای چت انتخاب کنید.</p>
            @endif
        </div>
    </div>
</div>
