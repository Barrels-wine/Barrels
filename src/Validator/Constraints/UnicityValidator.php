<?php

declare(strict_types=1);

namespace App\Validator\Constraints;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntityValidator;
use Symfony\Component\Validator\Constraint;

class UnicityValidator extends UniqueEntityValidator
{
    private $registry;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry);
        $this->registry = $registry;
    }

    public function validate($entity, Constraint $constraint)
    {
        $fields = (array) $constraint->fields;

        if (null === $entity) {
            return;
        }

        $em = $this->registry->getManagerForClass(get_class($entity));
        /** @var ClassMetadata $class */
        $class = $em->getClassMetadata(get_class($entity));

        $criteria = [];
        $hasNullValue = false;

        foreach ($fields as $fieldName) {
            $fieldValue = $class->reflFields[$fieldName]->getValue($entity);

            if (null === $fieldValue) {
                $hasNullValue = true;
            }

            if ($constraint->ignoreNull && null === $fieldValue) {
                continue;
            }

            $criteria[$fieldName] = $fieldValue;

            if (null !== $criteria[$fieldName] && $class->hasAssociation($fieldName)) {
                $em->initializeObject($criteria[$fieldName]);
            }
        }

        if ($hasNullValue && $constraint->ignoreNull) {
            return;
        }

        if (empty($criteria)) {
            return;
        }

        $repository = $em->getRepository(get_class($entity));

        $result = $repository->findBy($criteria);

        /* If no entity matched the query criteria or a single entity matched,
         * which has the same id as the entity being validated, the criteria is
         * unique.
         */
        if (0 === count($result) || (1 === count($result) && $entity->getId() === current($result)->getId())) {
            return;
        }

        $errorPath = null !== $constraint->errorPath ? $constraint->errorPath : $fields[0];
        $invalidValue = $criteria[$errorPath] ?? $criteria[$fields[0]];

        $this->context->buildViolation($constraint->message)
            ->atPath($errorPath)
            ->addViolation();
    }
}
