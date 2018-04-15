<?php

declare(strict_types=1);

namespace App\DataFixtures\Processor;

use App\Entity\User;
use Fidry\AliceDataFixtures\ProcessorInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

final class UserProcessor implements ProcessorInterface
{
    /** @var EncoderFactoryInterface */
    private $encoderFactory;

    public function __construct(EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
    }

    public function preProcess(string $id, $object): void
    {
        if (false === $object instanceof User) {
            return;
        }

        $encoded = $this
            ->encoderFactory
            ->getEncoder(User::class)
            ->encodePassword($object->getPassword(), null)
        ;
        $object->setPassword($encoded);
    }

    public function postProcess(string $id, $object): void
    {
    }
}
