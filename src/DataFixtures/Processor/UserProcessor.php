<?php

declare(strict_types = 1);

namespace App\DataFixtures\Processor;

use Fidry\AliceDataFixtures\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
    {}
}
