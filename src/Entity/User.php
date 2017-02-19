<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=25, unique=true)
     */
    private $username;
    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;
    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $email;
    /**
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @return User
     */
    public function setId($id) : User
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return User
     */
    public function setUsername(string $username) : User
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword() : string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail() : string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email) : User
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive() : bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     * @return User
     */
    public function setActive($active) : User
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return array
     */
    public function getRoles() : array
    {
        return array('ROLE_USER');
    }

    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->username,
            $this->email,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->username,
            $this->email,
            ) = unserialize($serialized);
    }
}