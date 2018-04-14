<?php

declare(strict_types = 1);

namespace App\DataFixtures\Processor;

use Fidry\AliceDataFixtures\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class UserProcessor implements ProcessorInterface
{
    /**
    * @var UserPasswordEncoderInterface
    */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function preProcess(string $id, $object): void
    {

        if (false === $object instanceof User) {
            return;
        }

        $encoded = $this->passwordEncoder->encodePassword($object, $object->getPassword());
        $object->setPassword($encoded);
    }

    public function postProcess(string $id, $object): void
    {}
}
