<?php

declare(strict_types=1);

namespace App\Reference;

class Designations
{
    const DESIGNATIONS = [
        'FR' => [

        ],
    ];

    public static function getByCountryAndRegion(string $country, string $region)
    {
        return self::DESIGNATIONS[$country][$region];
    }

    public static function getByCountry(string $country)
    {
        return self::DESIGNATIONS[$country];
    }
}
