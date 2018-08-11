<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class Unicity extends UniqueEntity
{
    public function validatedBy()
    {
        return UnicityValidator::class;
    }
}
