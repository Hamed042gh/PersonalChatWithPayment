<?php


use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat.{receiverId}.{senderId}', function ($user, $receiverId, $senderId) {
    return (int) $user->id === (int) $receiverId || (int) $user->id === (int) $senderId;
});
