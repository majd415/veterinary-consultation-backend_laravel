<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => '$2y$12$z38K0WFbHVUfWPzmzrfW9uPmHD4KCzwYVW6/UY7fz21mbypVvsA6u', // password
                'avatar' => 'images/users/user1.jpg',
            ]
        );

        $this->call([
            SliderOfferSeeder::class,
            StoreSeeder::class,
            TopRatedItemSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
