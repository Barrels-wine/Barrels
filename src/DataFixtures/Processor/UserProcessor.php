<?php

declare(strict_types = 1);

namespace App\DataFixtures\Processor;

//use Fidry\AliceDataFixtures\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

final class UserProcessor //implements ProcessorInterface
{
    /**
    * @var UserPasswordEncoder
    */
    private $passwordEncoder;

    /**
    * @param UserPasswordEncoder $passwordEncoder
    */
    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function preProcess(string $id, $object)
    {

        if (false === $object instanceof User) {
            return;
        }

        $encoded = $this->passwordEncoder->encodePassword($object, $object->getPassword());
        $object->setPassword($encoded);
    }

    public function postProcess(string $id, $object)
    {
        // TODO: Implement postProcess() method.
    }

}