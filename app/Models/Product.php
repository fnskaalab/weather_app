<?php

namespace App\Models;

use App\Enums\ProductWeatherCompatibilityEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static byWeatherCompatibility(mixed $is) using scope scopeByWeatherCompatibility()
 * @method static paginate(int $perPage)
 */
class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'weather_compatibility',
    ];

    protected $casts = [
        'weather_compatibility' => ProductWeatherCompatibilityEnum::class,
        'price' => 'float',
    ];

    public function scopeByWeatherCompatibility(Builder $query, float $temperature): Builder
    {
        if ($temperature < 10) {
            return $query->where('weather_compatibility', ProductWeatherCompatibilityEnum::COLD->value);
        } elseif ($temperature >= 10 && $temperature <= 20) {
            return $query->where('weather_compatibility', ProductWeatherCompatibilityEnum::NORMAL->value);
        } else {
            return $query->where('weather_compatibility', ProductWeatherCompatibilityEnum::HOT->value);
        }
    }
}
