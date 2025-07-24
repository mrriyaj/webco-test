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
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create regular user
        User::create([
            'name' => 'Test User',
            'email' => 'user@example.com',
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
