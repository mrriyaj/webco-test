<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Electronic devices and gadgets including smartphones, laptops, tablets, and accessories.',
                'external_url' => 'https://example.com/electronics',
            ],
            [
                'name' => 'Clothing',
                'description' => 'Fashion and apparel for men, women, and children including shirts, pants, dresses, and shoes.',
                'external_url' => 'https://example.com/clothing',
            ],
            [
                'name' => 'Home & Garden',
                'description' => 'Home improvement, furniture, gardening tools, and decorative items for indoor and outdoor spaces.',
                'external_url' => null,
            ],
            [
                'name' => 'Books',
                'description' => 'Physical and digital books across all genres including fiction, non-fiction, educational, and reference materials.',
                'external_url' => 'https://example.com/books',
            ],
            [
                'name' => 'Sports & Fitness',
                'description' => 'Sports equipment, fitness gear, outdoor recreation items, and athletic apparel.',
                'external_url' => null,
            ],
            [
                'name' => 'Beauty & Health',
                'description' => 'Cosmetics, skincare products, health supplements, and personal care items.',
                'external_url' => 'https://example.com/beauty',
            ],
            [
                'name' => 'Toys & Games',
                'description' => 'Children\'s toys, board games, video games, and educational play items.',
                'external_url' => null,
            ],
            [
                'name' => 'Automotive',
                'description' => 'Car parts, accessories, maintenance products, and automotive tools.',
                'external_url' => 'https://example.com/automotive',
            ],
            [
                'name' => 'Food & Beverages',
                'description' => 'Packaged foods, beverages, snacks, and specialty food items.',
                'external_url' => null,
            ],
            [
                'name' => 'Office Supplies',
                'description' => 'Stationery, office furniture, business equipment, and workplace essentials.',
                'external_url' => 'https://example.com/office',
            ],
        ];

        foreach ($categories as $category) {
            ProductCategory::create($category);
        }
    }
}
