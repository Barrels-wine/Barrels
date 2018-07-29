<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Reference\Designations;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidDesignationValidator extends ConstraintValidator
{
    const FR_ISO = 'FR';

    public function validate($protocol, Constraint $constraint)
    {
        $designations = [];

        if ($protocol->getCountry() === self::FR_ISO) {
            $designations = Designations::getByCountryAndRegion($protocol->getCountry(), $protocol->getRegions());
        } else {
            $designations = Designations::getByCountry($protocol->getCountry());
        }

        if (!\in_array($protocol->getDesignation(), $designations, true)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('designation')
                ->addViolation();
        }
    }
}
