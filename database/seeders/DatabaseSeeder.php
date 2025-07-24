<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user manually without factory
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'remember_token' => \Illuminate\Support\Str::random(10),
        ]);

        // Seed product colors
        $this->call(ProductColorSeeder::class);

        // Seed product categories
        $this->call(ProductCategorySeeder::class);

        // Seed product types
        $this->call(ProductTypeSeeder::class);

        // Call the ProductSeeder instead of using factory
        $this->call(ProductSeeder::class);
    }
}
