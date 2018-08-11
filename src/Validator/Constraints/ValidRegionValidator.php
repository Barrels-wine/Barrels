<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Reference\Designations;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidRegionValidator extends ConstraintValidator
{
    public const FR_ISO = 'FR';

    public function validate($protocol, Constraint $constraint)
    {
        if ($protocol->getCountry() !== self::FR_ISO) {
            return;
        }

        if (!\in_array($protocol->getRegion(), Designations::getFrenchRegions(), true)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('region')
                ->addViolation();
        }
    }
}
