<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ValidDesignation extends Constraint
{
    public $message = 'designation.not_valid';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
