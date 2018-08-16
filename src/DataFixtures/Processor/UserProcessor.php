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

    private $adminPassword;

    public function __construct(EncoderFactoryInterface $encoderFactory, $adminPassword)
    {
        $this->encoderFactory = $encoderFactory;
        $this->adminPassword = $adminPassword;
    }

    public function preProcess(string $id, $object): void
    {
        if (false === $object instanceof User) {
            return;
        }

        $rawPassword = $object->getUsername() === 'admin'
            ? $this->adminPassword
            : $object->getPassword()
        ;

        $encoded = $this
            ->encoderFactory
            ->getEncoder(User::class)
            ->encodePassword($rawPassword, null)
        ;
        $object->setPassword($encoded);
    }

    public function postProcess(string $id, $object): void
    {
    }
}
