<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopRatedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'type',
        'rating',
        'price',
    ];

    protected $casts = [
        'name' => 'array',
        'rating' => 'float',
        'price' => 'float',
    ];

    /**
     * Ensure name is returned correctly in JSON.
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array['name'] = is_string($this->name) ? json_decode($this->name, true) : $this->name;
        return $array;
    }
}
