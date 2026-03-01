<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TopRatedItem;

class TopRatedItemSeeder extends Seeder
{
    public function run()
    {
        TopRatedItem::truncate();

        $items = [
            [
                'name' => ['en' => 'Premium Pet Food', 'ar' => 'طعام حيوانات أليفة مميز'],
                'image' => 'images/top_rated/food.jpg',
                'type' => 'Product',
                'rating' => 5.0,
                'price' => 25.00,
            ],
            [
                'name' => ['en' => 'Luxury Grooming Kit', 'ar' => 'مجموعة تزيين فاخرة'],
                'image' => 'images/top_rated/shampoo.jpg',
                'type' => 'Product',
                'rating' => 4.9,
                'price' => 15.00,
            ],
            [
                'name' => ['en' => 'Dog Training Toy', 'ar' => 'لعبة تدريب الكلاب'],
                'image' => 'images/top_rated/toy.jpg',
                'type' => 'Product',
                'rating' => 4.8,
                'price' => 12.00,
            ],
            [
                'name' => ['en' => 'Hotel Stay Package', 'ar' => 'باقة إقامة فندقية'],
                'image' => 'images/top_rated/hotel.jpg',
                'type' => 'Service',
                'rating' => 4.7,
                'price' => 100.00,
            ],
        ];

        foreach ($items as $item) {
            TopRatedItem::create($item);
        }
    }
}
