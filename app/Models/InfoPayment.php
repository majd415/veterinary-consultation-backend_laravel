<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'payment_method',
        'transaction_id',
        'status',
        'type',
        'description',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
