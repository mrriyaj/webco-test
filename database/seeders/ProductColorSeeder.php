<?php

namespace Database\Seeders;

use App\Models\ProductColor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $colors = [
            [
                'name' => 'Red',
                'description' => 'Classic red color',
                'hex_code' => '#FF0000',
            ],
            [
                'name' => 'Blue',
                'description' => 'Deep blue color',
                'hex_code' => '#0000FF',
            ],
            [
                'name' => 'Green',
                'description' => 'Natural green color',
                'hex_code' => '#00FF00',
            ],
            [
                'name' => 'Black',
                'description' => 'Pure black color',
                'hex_code' => '#000000',
            ],
            [
                'name' => 'White',
                'description' => 'Pure white color',
                'hex_code' => '#FFFFFF',
            ],
            [
                'name' => 'Yellow',
                'description' => 'Bright yellow color',
                'hex_code' => '#FFFF00',
            ],
            [
                'name' => 'Purple',
                'description' => 'Royal purple color',
                'hex_code' => '#800080',
            ],
            [
                'name' => 'Orange',
                'description' => 'Vibrant orange color',
                'hex_code' => '#FFA500',
            ],
            [
                'name' => 'Pink',
                'description' => 'Soft pink color',
                'hex_code' => '#FFC0CB',
            ],
            [
                'name' => 'Gray',
                'description' => 'Neutral gray color',
                'hex_code' => '#808080',
            ],
        ];

        foreach ($colors as $color) {
            ProductColor::create($color);
        }
    }
}
