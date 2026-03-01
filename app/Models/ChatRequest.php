<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'status',
        'taken_by',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function user()
    {
        return $this->customer();
    }

    public function vet()
    {
        return $this->belongsTo(User::class, 'taken_by');
    }

    public function chatRoom()
    {
        return $this->hasOne(ChatRoom::class);
    }
}
