<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HotelBooking extends Model
{
    use HasTranslations;

    protected $table = 'hotel_booking';

    protected $fillable = [
        'user_id',
        'check_in_date',
        'check_in_time',
        'check_out_date',
        'check_out_time',
        'owner_name',
        'owner_phone',
        'num_pets',
        'pet_type',
        'total_days',
        'total_cost',
        'status',
        'payment_status',
    ];

    public $translatable = ['pet_type'];

    protected $casts = [
        'total_cost' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
