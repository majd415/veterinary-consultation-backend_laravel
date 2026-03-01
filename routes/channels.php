<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\ChatRoom;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat.room.{id}', function ($user, $id) {
    $chatRoom = ChatRoom::find($id);
    if (!$chatRoom) return false;
    
    return $user->id == $chatRoom->customer_id || $user->id == $chatRoom->vet_id;
});

Broadcast::channel('user.notifications.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
