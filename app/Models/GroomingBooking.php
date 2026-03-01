<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class GroomingBooking extends Model
{
    use HasTranslations;

    protected $table = 'shave_bath_booking';

    protected $fillable = [
        'user_id',
        'pickup_date',
        'pickup_time',
        'delivery_date',
        'delivery_time',
        'client_name',
        'client_phone',
        'num_animals',
        'animal_type',
        'status',
        'payment_status',
    ];

    public $translatable = ['animal_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
