<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SliderOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data to avoid duplicates if run multiple times
        DB::table('slider_images_offers')->truncate();

        $images = [
            [
                'image_url' => 'images/sliders/slider1.jpg',
                'link_url' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image_url' => 'images/sliders/slider2.jpg',
                'link_url' => null,
                'status' => true, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image_url' => 'images/sliders/slider3.jpg',
                'link_url' => null,
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('slider_images_offers')->insert($images);
    }
}
