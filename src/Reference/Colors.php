<?php

declare(strict_types=1);

namespace App\Reference;

class Colors
{
    public const RED = 'red';
    public const ROSE = 'rose';
    public const WHITE = 'white';
    public const RANCIO = 'rancio';
    public const GREY = 'grey';
    public const BRICK = 'brick';
    public const AMBER = 'amber';
    public const GARNET = 'garnet';

    public static function getConstants()
    {
        return [self::RED, self::ROSE, self::WHITE, self::RANCIO, self::GREY, self::BRICK, self::AMBER, self::GARNET];
    }
}
