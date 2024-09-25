<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

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
        return new PrivateChannel('chat.' . $this->message->receiver_id . '.' . $this->message->sender_id);
    }


    //event
    public function broadcastAs()
    {
        return 'PersonalChatEvent';
    }

    public function broadcastWith()
    {
        return [
            'content' => $this->message->content,
            'sender_id' => $this->message->sender_id,
            'receiver_id' => $this->message->receiver_id,
            'timestamp' => now(),

        ];
    }
}
