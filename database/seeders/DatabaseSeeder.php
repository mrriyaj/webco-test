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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'), // Ensure to hash the password
        ]);

        // Seed product colors
        $this->call(ProductColorSeeder::class);

        // Seed product categories
        $this->call(ProductCategorySeeder::class);

        // Seed product types
        $this->call(ProductTypeSeeder::class);

        // Seed products (must be last since it depends on categories and colors)
        $this->call(ProductSeeder::class);
    }
}
