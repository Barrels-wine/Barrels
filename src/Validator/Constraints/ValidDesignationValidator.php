<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use App\Reference\Designations;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidDesignationValidator extends ConstraintValidator
{
    public function validate($protocol, Constraint $constraint)
    {
        $designations = [];

        if (!$protocol->getCountry()) {
            return;
        }

        if ($protocol->getCountry() === 'FR') {
            if (!$protocol->getRegion()) {
                return;
            }
            $designations = Designations::getByCountryAndRegion($protocol->getCountry(), $protocol->getRegion());
        } else {
            $designations = Designations::getByCountry($protocol->getCountry());
        }

        if (!\count($designations)) {
            return;
        }

        if (!\in_array($protocol->getDesignation(), $designations, true)) {
            $this->context->buildViolation($constraint->message)
                ->atPath('designation')
                ->addViolation();
        }
    }
}
