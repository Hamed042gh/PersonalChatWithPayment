<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Message;
use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PersonalChat extends Component
{
    public $user;
    public $users;
    public $messages = [];
    public $newMessage;
    public $selectedUser;
    
    public function mount()
    {

        $this->user = Auth::user();

        $users = $this->users = User::where('id', '!=', $this->user->id)->get();
      
    }


    public function chooseUser($user_id)
    {
       $r = $this->selectedUser = User::find($user_id);
        $this->loadMessages();
        
    }


    public function loadMessages()
    {
        if ($this->selectedUser) {
            $this->messages = Message::where(function ($query) {
                $query->where('sender_id', $this->user->id)
                    ->where('receiver_id', $this->selectedUser->id);
            })->orWhere(function ($query) {
                $query->where('sender_id', $this->selectedUser->id)
                    ->where('receiver_id', $this->user->id);
            })->latest()->get()->toArray();
        }
    }


    public function handleMessageSubmission()
    {
        $validateData = $this->validate([
            'newMessage' => 'required|max:200'
        ]);

        Message::Create([
            'content' => $this->newMessage,
            'sender_id' => $this->user->id,
            'receiver_id' => $this->selectedUser->id,
        ]);

        $this->newMessage = '';

        $this->loadMessages();
    }





    public function render()
    {
        return view('livewire.personal-chat');
    }
}
