<?php

namespace App\Enums;

enum ProductWeatherCompatibilityEnum: string
{
    case COLD = 'cold';
    case NORMAL = 'normal';
    case HOT = 'hot';

    public static function values(): array
    {
        return array_map(fn ($case) => $case->value, self::cases());
    }

    public static function isValid(string $value): bool
    {
        return in_array($value, self::values(), true);
    }
}
