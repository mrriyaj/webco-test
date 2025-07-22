<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductColor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    // Get some categories and colors to use for products
    $electronics = ProductCategory::where('name', 'Electronics')->first();
    $clothing = ProductCategory::where('name', 'Clothing')->first();
    $homeGarden = ProductCategory::where('name', 'Home & Garden')->first();
    $sportsF = ProductCategory::where('name', 'Sports & Fitness')->first();

    $red = ProductColor::where('name', 'Red')->first();
    $blue = ProductColor::where('name', 'Blue')->first();
    $black = ProductColor::where('name', 'Black')->first();
    $white = ProductColor::where('name', 'White')->first();
    $green = ProductColor::where('name', 'Green')->first();

    $products = [
      [
        'name' => 'Wireless Bluetooth Headphones',
        'product_category_id' => $electronics?->id ?? 1,
        'product_color_id' => $black?->id ?? 1,
        'description' => 'High-quality wireless headphones with noise cancellation and 20-hour battery life.',
      ],
      [
        'name' => 'Smart Fitness Watch',
        'product_category_id' => $electronics?->id ?? 1,
        'product_color_id' => $blue?->id ?? 2,
        'description' => 'Advanced fitness tracker with heart rate monitoring, GPS, and sleep tracking.',
      ],
      [
        'name' => 'Cotton T-Shirt',
        'product_category_id' => $clothing?->id ?? 2,
        'product_color_id' => $white?->id ?? 5,
        'description' => '100% organic cotton t-shirt with comfortable fit and breathable fabric.',
      ],
      [
        'name' => 'Running Shoes',
        'product_category_id' => $sportsF?->id ?? 5,
        'product_color_id' => $red?->id ?? 1,
        'description' => 'Professional running shoes with advanced cushioning and grip technology.',
      ],
      [
        'name' => 'Garden Hose',
        'product_category_id' => $homeGarden?->id ?? 3,
        'product_color_id' => $green?->id ?? 3,
        'description' => 'Durable 50-foot garden hose with spray nozzle and kink-resistant design.',
      ],
      [
        'name' => 'Laptop Computer',
        'product_category_id' => $electronics?->id ?? 1,
        'product_color_id' => $black?->id ?? 4,
        'description' => '15-inch laptop with Intel i7 processor, 16GB RAM, and 512GB SSD storage.',
      ],
      [
        'name' => 'Yoga Mat',
        'product_category_id' => $sportsF?->id ?? 5,
        'product_color_id' => $blue?->id ?? 2,
        'description' => 'Non-slip yoga mat with extra cushioning for comfortable practice sessions.',
      ],
      [
        'name' => 'Ceramic Coffee Mug',
        'product_category_id' => $homeGarden?->id ?? 3,
        'product_color_id' => $white?->id ?? 5,
        'description' => 'Handcrafted ceramic coffee mug with ergonomic handle and heat retention design.',
      ],
      [
        'name' => 'Denim Jeans',
        'product_category_id' => $clothing?->id ?? 2,
        'product_color_id' => $blue?->id ?? 2,
        'description' => 'Classic straight-leg denim jeans with comfortable fit and durable construction.',
      ],
      [
        'name' => 'Smartphone Case',
        'product_category_id' => $electronics?->id ?? 1,
        'product_color_id' => $red?->id ?? 1,
        'description' => 'Protective smartphone case with shock absorption and wireless charging compatibility.',
      ],
    ];

    foreach ($products as $product) {
      Product::create($product);
    }
  }
}
