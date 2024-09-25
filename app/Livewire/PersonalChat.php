<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use App\Events\PersonalChatEvent;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Carbon\Carbon;


class PersonalChat extends Component
{
    public $user;
    public $users;
    public $messages = [];
    public $newMessage;
    public $selectedUserId;
    public $selectedUser;

    protected $listeners = ['addMessage', 'refreshMessages'];

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
            'receiver_id' => $this->selectedUser->id,
        ]);

        broadcast(new PersonalChatEvent($this->user->id, $message));

        $this->newMessage = '';
        $this->loadMessages();
    }

    protected function validateMessage()
    {
        return $this->validate([
            'newMessage' => 'required|max:200'
        ]);
    }

    public function addMessage($event)
    {
        $this->messages[] = [
            'content' => $event['content'],
            'sender_id' => $event['sender_id'],
            'receiver_id' => $event['receiver_id'],
            'timestamp' => $event['timestamp'],
        ];
    }

    public function refreshMessages()
    {
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.personal-chat');
    }
}
