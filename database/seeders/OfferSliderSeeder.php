<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\OfferSlider;

class OfferSliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $offers = [
            [
                'image_url' => 'assets/images/slider_hotel.png', // Local placeholder
                'link_url' => null,
                'status' => true,
            ],
            [
                'image_url' => 'https://images.unsplash.com/photo-1548191265-cc70d3d45ba1?q=80&w=1000&auto=format&fit=crop',
                'link_url' => null,
                'status' => true,
            ],
            [
                'image_url' => 'https://images.unsplash.com/photo-1516734212186-a967f81ad0d7?q=80&w=1000&auto=format&fit=crop',
                'link_url' => null,
                'status' => true,
            ],
        ];

        foreach ($offers as $offer) {
            OfferSlider::create($offer);
        }
    }
}
