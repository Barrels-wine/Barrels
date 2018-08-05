<?php

declare(strict_types=1);

namespace App\Reference;

class Categories
{
    public const WINE = 'wine';
    public const CHAMPAGNE = 'champagne';
    public const SWEET = 'sweet';
    public const SPIRIT = 'spirit';

    public static function getConstants()
    {
        return [
            self::WINE,
            self::CHAMPAGNE,
            self::SWEET,
            self::SPIRIT,
        ];
    }
}
