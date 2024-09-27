<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Events\PersonalChatEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PersonalChat extends Component
{
    public $user;
    public $users;
    public $messages = [];
    public $newMessage;
    public $selectedUserId;
    public $selectedUser;

    public function mount()
    {
        $this->user = Auth::user();
        $this->users = User::where('id', '!=', $this->user->id)->get();

        if ($this->user) {
            $this->user->is_online = true;
            $this->user->save();
        }
    }

    public function chooseUser($user_id)
    {
        $this->dispatch('userSelected', $user_id);
    }

    #[On('userSelected')]
    public function handleUserSelected($selectedUserId)
    {
        $this->selectedUserId = $selectedUserId;
        $this->selectedUser = User::findOrFail($selectedUserId);
        $this->loadMessages();
    }

    #[On('addMessage')]
    public function addMessage($message)
    {
        $this->messages[] = $message;
    }

    public function loadMessages()
    {
        if ($this->selectedUser) {
            $this->messages = Message::where(function ($query) {
                $query->whereIn('sender_id', [$this->user->id, $this->selectedUser->id])
                    ->whereIn('receiver_id', [$this->user->id, $this->selectedUser->id]);
            })->latest()->get()->toArray();
        }
    }

    public function handleMessageSubmission()
    {
        $this->validateMessage();

        $message = Message::create([
            'content' => $this->newMessage,
            'sender_id' => $this->user->id,
            'receiver_id' => $this->selectedUser->id
        ]);

        // Broadcast the message and user status
        broadcast(new PersonalChatEvent($this->user->id, $message)); 
        Log::info('Message broadcasted', ['message' => $message]);

        $this->messages[] = $message;
        $this->newMessage = '';
        $this->dispatch('addMessage', $message->toArray());
        $this->loadMessages();
    }

    // Validate the message input
    protected function validateMessage()
    {
        return $this->validate([
            'newMessage' => 'required|max:200'
        ]);
    }

    // Render the component
    public function render()
    {
        return view('livewire.personal-chat', [
            'messages' => $this->messages,
            'users' => $this->users,
        ]);
    }
}
