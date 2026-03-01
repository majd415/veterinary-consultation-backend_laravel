<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'vet_id',
        'chat_request_id',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vet()
    {
        return $this->belongsTo(User::class, 'vet_id');
    }

    public function chatRequest()
    {
        return $this->belongsTo(ChatRequest::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
