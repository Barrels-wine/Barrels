<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ValidRegion extends Constraint
{
    public $message = 'region.not_valid';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
