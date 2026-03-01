<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    use HasFactory, HasTranslations;

    public $translatable = ['name', 'description'];

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'rate',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
