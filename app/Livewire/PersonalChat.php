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
    protected $listeners = ['new' => 'loadMessages'];


    public function mount()
    {
        $this->user = Auth::user();
        $this->users = User::where('id', '!=', $this->user->id)->get();
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


    public function loadMessages()
    {
        if ($this->selectedUser) {
            $this->messages = Message::where(function ($query) {
                $query->where(function ($query) {
                    $query->where('sender_id', $this->user->id)
                        ->where('receiver_id', $this->selectedUser->id);
                })
                    ->orWhere(function ($query) {
                        $query->where('sender_id', $this->selectedUser->id)
                            ->where('receiver_id', $this->user->id);
                    });
            })->latest()->get()->toArray();
        }

        Log::info('Loaded messages', ['messages' => $this->messages]);
    }

    public function handleMessageSubmission()
    {
        $this->validateMessage();

        $message = Message::create([
            'content' => $this->newMessage,
            'sender_id' => $this->user->id,
            'receiver_id' => $this->selectedUser->id
        ]);


        broadcast(new PersonalChatEvent($this->user->id, $message));
        Log::info('Message broadcasted', ['message' => $message]);
        $this->messages[] = $message->toArray();
        $this->newMessage = '';
        $this->loadMessages();
    }


    protected function validateMessage()
    {
        return $this->validate([
            'newMessage' => 'required|max:200'
        ]);
    }


    public function render()
    {

        return view('livewire.personal-chat', [
            'messages' => $this->messages
        ]);
    }
}
