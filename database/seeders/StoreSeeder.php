<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks to allow truncation
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Product::truncate();
        \App\Models\ProductCategory::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // 1. Create Categories
        $categories = [
            ['en' => 'Dog Food', 'ar' => 'طعام كلاب'],
            ['en' => 'Cat Food', 'ar' => 'طعام قطط'],
            ['en' => 'Bird Food', 'ar' => 'طعام طيور'],
        ];

        foreach ($categories as $catName) {
            $category = \App\Models\ProductCategory::create(['name' => $catName]);

            // 2. Create Sample Products for each category
            $images = ['food.jpg', 'shampoo.jpg', 'toy.jpg'];

            for ($i = 1; $i <= 5; $i++) {
                $image = $images[($i - 1) % count($images)];
                
                \App\Models\Product::create([
                    'category_id' => $category->id,
                    'name' => [
                        'en' => "{$catName['en']} Product $i",
                        'ar' => "{$catName['ar']} منتج $i",
                    ],
                    'description' => [
                        'en' => "This is a high quality {$catName['en']} for your pet.",
                        'ar' => "هذا طعام عالي الجودة للحيوانات الأليفة $i.",
                    ],
                    'price' => rand(10, 100) + 0.99,
                    'rate' => rand(300, 500) / 100, // 3.00 to 5.00
                    'image' => "images/products/$image", 
                ]);
            }
        }
    }
}
