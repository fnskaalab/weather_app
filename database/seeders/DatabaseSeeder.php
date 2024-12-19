<?php

namespace Database\Seeders;

use App\Enums\ProductWeatherCompatibilityEnum;
use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $products = [
            [
                'name' => 'T shirt',
                'weather_compatibility' => ProductWeatherCompatibilityEnum::HOT->value,
            ],
            [
                'name' => 'Sweat',
                'weather_compatibility' => ProductWeatherCompatibilityEnum::NORMAL->value,
            ],
            [
                'name' => 'Pull',
                'weather_compatibility' => ProductWeatherCompatibilityEnum::COLD->value,
            ],
        ];

        $colors = [
            'rouge', 'bleu', 'noir', 'blanc',
        ];

        foreach ($products as $product) {
            foreach ($colors as $color) {
                Product::create([
                    'name' => $product['name'].' '.$color,
                    'price' => fake()->randomFloat(),
                    'weather_compatibility' => $product['weather_compatibility'],
                ]);
            }
        }
    }
}
