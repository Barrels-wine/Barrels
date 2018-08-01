<?php

declare(strict_types=1);

namespace App\Reference;

class Designations
{
    public const DESIGNATIONS = [
        'FR' => [
            'Bourgogne' => [
                'Bourgogne aligoté',
            ],
        ],
    ];

    public static function getByCountry(string $country)
    {
        if (!\array_key_exists($country, self::DESIGNATIONS)) {
            return [];
        }

        return self::DESIGNATIONS[$country];
    }

    public static function getByCountryAndRegion(string $country, string $region)
    {
        $byCountry = self::getByCountry($country);
        if (!\array_key_exists($region, $byCountry)) {
            return [];
        }

        return $byCountry[$region];
    }
}
