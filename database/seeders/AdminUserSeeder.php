<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists to avoid duplicates
        $admin = User::where('email', 'admin@dogmarket.com')->first();

        if (!$admin) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'admin@dogmarket.com',
                'password' => Hash::make('password'), // Change this in production
                'role' => 'vet', // or 'user', doesn't matter for admin access usually, but let's keep it valid
                'is_admin' => true,
                'email_verified_at' => now(),
            ]);
            $this->command->info('Admin user created: admin@dogmarket.com / password');
        } else {
            // Ensure existing user has admin access
            $admin->update(['is_admin' => true]);
            $this->command->info('Existing user updated to Admin: admin@dogmarket.com');
        }
    }
}
