<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Normalizer\EntityNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer as BaseSerializer;

class Serializer
{
    /** @var BaseSerializer */
    protected $serializer;

    /** @var EntityNormalizer */
    protected $entityNormalizer;

    public function __construct(EntityNormalizer $entityNormalizer)
    {
        $encoder = new JsonEncoder();

        $this->entityNormalizer = $entityNormalizer;
        $this->entityNormalizer->setCallbacks(['createdAt' => function ($dateTime) {
            return $dateTime instanceof \DateTime
                ? $dateTime->format(\DateTime::ATOM)
                : null;
        }]);
        $this->entityNormalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });

        $this->serializer = new BaseSerializer([$this->entityNormalizer], [$encoder]);
    }

    public function serialize($data)
    {
        return $this->serializer->serialize($data, 'json');
    }
}
