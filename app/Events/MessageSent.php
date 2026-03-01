<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $chatRoom = $this->message->chatRoom;
        $recipientId = ($this->message->user_id == $chatRoom->customer_id) 
            ? $chatRoom->vet_id 
            : $chatRoom->customer_id;

        return [
            new PrivateChannel('chat.room.' . $this->message->chat_room_id),
            new PrivateChannel('user.notifications.' . $recipientId),
        ];
    }
    
    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    // public function broadcastWith()
    // {
    //     return [
    //         'message' => $this->message->load('user'),
    //     ];
    // }
    public function broadcastWith()
{
    return [
        'message' => [
            'id' => $this->message->id,
            'chat_room_id' => $this->message->chat_room_id,
            'user_id' => $this->message->user_id,
            'message' => $this->message->message,
            'type' => $this->message->type,
            'image_url' => $this->message->image_url,
            'created_at' => $this->message->created_at->toDateTimeString(),
            'user' => [
                'id' => $this->message->user->id,
                'name' => $this->message->user->name,
                'email' => $this->message->user->email,
            ],
        ],
    ];
}

}
