<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Physical Product',
                'api_unique_number' => 'PT-PHYSICAL001',
            ],
            [
                'name' => 'Digital Product',
                'api_unique_number' => 'PT-DIGITAL001',
            ],
            [
                'name' => 'Service',
                'api_unique_number' => 'PT-SERVICE001',
            ],
            [
                'name' => 'Subscription',
                'api_unique_number' => 'PT-SUBSCRIPTION001',
            ],
            [
                'name' => 'Bundle',
                'api_unique_number' => 'PT-BUNDLE001',
            ],
            [
                'name' => 'Virtual Product',
                'api_unique_number' => 'PT-VIRTUAL001',
            ],
            [
                'name' => 'Downloadable',
                'api_unique_number' => 'PT-DOWNLOAD001',
            ],
            [
                'name' => 'Rental',
                'api_unique_number' => 'PT-RENTAL001',
            ],
            [
                'name' => 'Gift Card',
                'api_unique_number' => 'PT-GIFTCARD001',
            ],
            [
                'name' => 'Course',
                // This will auto-generate since api_unique_number is not provided
            ],
        ];

        foreach ($types as $type) {
            ProductType::create($type);
        }
    }
}
