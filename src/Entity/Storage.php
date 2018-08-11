<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity()
 * @ORM\Table(name="storage")
 */
class Storage
{
    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Bottle", mappedBy="storageLocation", cascade={"persist"})
     * @MaxDepth(1)
     */
    private $bottles;

    /**
     * @ORM\Column(name="capacity", type="integer", nullable=true)
     */
    private $capacity;

    public function __construct()
    {
        $this->bottles = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getBottles()
    {
        return $this->bottles;
    }

    public function setBottles($bottles)
    {
        $this->bottles = $bottles;

        return $this;
    }

    public function getCapacity()
    {
        return $this->capacity;
    }

    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function update(Storage $location)
    {
        $this->name = $location->getName();
        $this->description = $location->getDescription();
        $this->capacity = $location->getCapacity();
    }
}
