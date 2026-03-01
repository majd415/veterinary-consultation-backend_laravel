<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferSlider extends Model
{
    protected $table = 'slider_images_offers';

    protected $fillable = [
        'title',
        'image_url',
        'link_url',
        'status',
    ];

    protected $casts = [
        'title' => 'array',
        'status' => 'boolean',
    ];
}
