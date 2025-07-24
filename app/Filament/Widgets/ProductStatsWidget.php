<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductColor;
use App\Models\ProductType;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ProductStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->description('Total products in system')
                ->descriptionIcon('heroicon-m-cube')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Product Categories', ProductCategory::count())
                ->description('Available categories')
                ->descriptionIcon('heroicon-m-tag')
                ->color('success')
                ->chart([3, 5, 8, 4, 6, 9, 7]),

            Stat::make('Product Colors', ProductColor::count())
                ->description('Available colors')
                ->descriptionIcon('heroicon-m-swatch')
                ->color('warning')
                ->chart([2, 4, 6, 3, 8, 5, 9]),

            Stat::make('Product Types', ProductType::count())
                ->description('Available types')
                ->descriptionIcon('heroicon-m-squares-2x2')
                ->color('info')
                ->chart([1, 3, 5, 2, 7, 4, 8]),
        ];
    }
}
