<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PersonalChatEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $message;

    public function __construct($user, $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    //channel
    public function broadcastOn()
    {
        return new PresenceChannel('chat.' . min($this->message['receiver_id'], $this->message['sender_id']) . '.' . max($this->message['receiver_id'], $this->message['sender_id']));
    }


    //event
    public function broadcastAs()
    {
        return 'PersonalChatEvent';
    }

    public function broadcastWith()
    {
        $sender = User::find($this->message['sender_id']);
        $receiver = User::find($this->message['receiver_id']);
    
        return [
            'content' => $this->message['content'],
            'sender_id' => $this->message['sender_id'],
            'receiver_id' => $this->message['receiver_id'],
            'created_at' => $this->message['created_at'],
            'updated_at' =>  $this->message['updated_at'],
            'id' =>  $this->message['id'],
            'sender_is_online' => $sender->is_online,
            'receiver_is_online' => $receiver->is_online,
        ];
    }
    
}
