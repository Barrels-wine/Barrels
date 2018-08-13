<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity()
 * @ORM\Table(name="bottle")
 */
class Bottle
{
    public const DEFAULT_VOLUME = '75cl';

    /**
     * @ORM\Column(type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Wine", inversedBy="bottles", cascade={"persist"})
     * @ORM\JoinColumn(name="wine_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
     * @MaxDepth(1)
     */
    private $wine;

    /**
     * @ORM\Column(name="acquisition_price", type="integer", nullable=true)
     */
    private $acquisitionPrice = null;

    /**
     * @ORM\Column(name="estimation_price", type="integer", nullable=true)
     */
    private $estimationPrice = null;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $volume = self::DEFAULT_VOLUME;

    /**
     * @ORM\ManyToOne(targetEntity="Storage", inversedBy="bottles")
     * @ORM\JoinColumn(name="storage_location_id")
     * @MaxDepth(1)
     */
    private $storageLocation = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function __clone()
    {
        $this->setId(null);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getWine(): ?Wine
    {
        return $this->wine;
    }

    public function setWine(Wine $wine = null): self
    {
        $this->wine = $wine;

        return $this;
    }

    public function getAcquisitionPrice(): ?int
    {
        return $this->acquisitionPrice;
    }

    public function setAcquisitionPrice(int $acquisitionPrice = null): self
    {
        $this->acquisitionPrice = $acquisitionPrice;

        return $this;
    }

    public function getEstimationPrice(): ?int
    {
        return $this->estimationPrice;
    }

    public function setEstimationPrice(int $estimationPrice = null): self
    {
        $this->estimationPrice = $estimationPrice;

        return $this;
    }

    public function getVolume(): string
    {
        return $this->volume;
    }

    public function setVolume(string $volume): self
    {
        $this->volume = $volume;

        return $this;
    }

    public function getStorageLocation(): ?Storage
    {
        return $this->storageLocation;
    }

    public function setStorageLocation(Storage $storageLocation = null)
    {
        $this->storageLocation = $storageLocation;

        return $this;
    }

    public function update(self $bottle)
    {
        $this->wine = $bottle->getWine();
        $this->acquisitionPrice = $bottle->getAcquisitionPrice();
        $this->estimationPrice = $bottle->getEstimationPrice();
        $this->volume = $bottle->getVolume();
        $this->storageLocation = $bottle->getStorageLocation();
    }
}
