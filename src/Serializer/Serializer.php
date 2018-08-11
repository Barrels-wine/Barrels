<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Normalizer\EntityNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer as BaseSerializer;

class Serializer
{
    /** @var BaseSerializer */
    protected $serializer;

    /** @var EntityNormalizer */
    protected $entityNormalizer;

    public function reduce($object)
    {
        return $object ? $object->getId() : null;
    }

    public function normalizeDate($dateTime)
    {
        return $dateTime instanceof \DateTime
            ? $dateTime->format(\DateTime::ATOM)
            : null;
    }

    public function __construct(EntityNormalizer $entityNormalizer)
    {
        $encoder = new JsonEncoder();

        $this->entityNormalizer = $entityNormalizer;
        $this->entityNormalizer->setCallbacks(['createdAt' => [$this, 'normalizeDate']]);
        $this->entityNormalizer->setCircularReferenceHandler([$this, 'reduce']);
        $this->entityNormalizer->setMaxDepthHandler([$this, 'reduce']);

        $this->serializer = new BaseSerializer([$this->entityNormalizer], [$encoder]);
    }

    public function serialize($data)
    {
        return $this->serializer->serialize($data, 'json', [ObjectNormalizer::ENABLE_MAX_DEPTH => true]);
    }
}
