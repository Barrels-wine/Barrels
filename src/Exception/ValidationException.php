<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \RuntimeException implements HttpExceptionInterface
{
    public const MESSAGE = 'Validation failed';

    /** @var ConstraintViolationListInterface */
    private $validationList;

    public function __construct(ConstraintViolationListInterface $validationList)
    {
        parent::__construct(self::MESSAGE);
        $this->validationList = $validationList;
    }

    public function getStatusCode()
    {
        return Response::HTTP_BAD_REQUEST;
    }

    public function getHeaders()
    {
        return [];
    }

    public function getViolations()
    {
        $violations = [];

        /** @var ConstraintViolationInterface $violation */
        foreach ($this->validationList as $violation) {
            // Replace [] in path for when we validate arrays
            $violations[str_replace(['[', ']'], '', $violation->getPropertyPath())] = [
                'message' => $violation->getMessage(),
            ];
        }

        return $violations;
    }
}
