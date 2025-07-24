<?php

namespace Database\Factories;

use App\Models\ProductCategory;
use App\Models\ProductColor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $productNames = [
            'Wireless Earbuds',
            'Smart Phone',
            'Laptop Computer',
            'Gaming Monitor',
            'Bluetooth Speaker',
            'Fitness Tracker',
            'Digital Camera',
            'Tablet Device',
            'Smart Watch',
            'Wireless Charger',
            'USB Cable',
            'Memory Card',
            'Power Bank',
            'Keyboard',
            'Computer Mouse',
            'Webcam',
            'Microphone',
            'Desk Lamp',
            'Office Chair',
            'Standing Desk',
            'Coffee Maker',
            'Blender',
            'Air Purifier',
            'Humidifier',
            'Space Heater',
            'Fan',
            'Vacuum Cleaner',
            'Robot Vacuum',
            'Smart Thermostat',
            'Security Camera'
        ];

        return [
            'name' => $this->faker->randomElement($productNames) . ' ' . $this->faker->word(),
            'product_category_id' => ProductCategory::inRandomOrder()->first()->id,
            'product_color_id' => ProductColor::inRandomOrder()->first()->id,
            'description' => $this->faker->paragraph(3),
        ];
    }

}
