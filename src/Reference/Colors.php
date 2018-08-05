<?php

declare(strict_types=1);

namespace App\Reference;

class Colors
{
    public const RED = 'red';
    public const ROSE = 'rose';
    public const WHITE = 'white';

    public static function getConstants()
    {
        return [self::RED, self::ROSE, self::WHITE];
    }
}
