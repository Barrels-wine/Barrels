<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity()
 * @ORM\Table(name="wine")
 */
class Wine
{
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
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $designation;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $varietals;

    /**
     * @ORM\Column(type="string")
     */
    private $color;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vintage;

    /**
     * @ORM\Column(type="string")
     */
    private $country;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $winemaker;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $rating = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment = null;

    /**
     * @ORM\Column(name="food_pairing", type="text", nullable=true)
     */
    private $foodPairing = null;

    /**
     * @ORM\Column(name="classification_level" ,type="string", nullable=true)
     */
    private $classificationLevel = null;

    /**
     * @ORM\Column(name="drink_from", type="integer", nullable=true)
     */
    private $drinkFrom;

    /**
     * @ORM\Column(name="drink_to", type="integer", nullable=true)
     */
    private $drinkTo;

    /**
     * @ORM\Column(name="climax_from", type="integer", nullable=true)
     */
    private $climaxFrom;

    /**
     * @ORM\Column(name="climax_to", type="integer", nullable=true)
     */
    private $climaxTo;

    /**
     * @ORM\Column(name="alcohol_degree", type="float", nullable=true)
     */
    private $alcoholDegree = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $temperature = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $batch = null;

    /**
     * @ORM\Column(type="string")
     */
    private $category;

    /**
     * @ORM\OneToMany(targetEntity="Bottle", mappedBy="wine", cascade={"remove", "persist"}, orphanRemoval=true)
     * @MaxDepth(1)
     */
    private $bottles;

    public function __construct()
    {
        $this->bottles = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?string
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation = null): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getVarietals(): ?array
    {
        return $this->varietals;
    }

    public function setVarietals(array $varietals = null)
    {
        $this->varietals = $varietals;

        return $this;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getVintage(): ?int
    {
        return $this->vintage;
    }

    public function setVintage(int $vintage = null): self
    {
        $this->vintage = $vintage;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region = null): self
    {
        $this->region = $region;

        return $this;
    }

    public function getWinemaker(): ?string
    {
        return $this->winemaker;
    }

    public function setWinemaker(string $winemaker = null): self
    {
        $this->winemaker = $winemaker;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating = null): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment = null): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getFoodPairing(): ?string
    {
        return $this->foodPairing;
    }

    public function setFoodPairing(string $foodPairing = null): self
    {
        $this->foodPairing = $foodPairing;

        return $this;
    }

    public function getClassificationLevel(): ?string
    {
        return $this->classificationLevel;
    }

    public function setClassificationLevel(string $classificationLevel = null): self
    {
        $this->classificationLevel = $classificationLevel;

        return $this;
    }

    public function getDrinkFrom(): ?int
    {
        return $this->drinkFrom;
    }

    public function setDrinkFrom(int $drinkFrom = null): self
    {
        $this->drinkFrom = $drinkFrom;

        return $this;
    }

    public function getDrinkTo(): ?int
    {
        return $this->drinkTo;
    }

    public function setDrinkTo(int $drinkTo = null): self
    {
        $this->drinkTo = $drinkTo;

        return $this;
    }

    public function getClimaxFrom(): ?int
    {
        return $this->climaxFrom;
    }

    public function setClimaxFrom(int $climaxFrom = null): self
    {
        $this->climaxFrom = $climaxFrom;

        return $this;
    }

    public function getClimaxTo(): ?int
    {
        return $this->climaxTo;
    }

    public function setClimaxTo(int $climaxTo = null): self
    {
        $this->climaxTo = $climaxTo;

        return $this;
    }

    public function getAlcoholDegree(): ?float
    {
        return $this->alcoholDegree;
    }

    public function setAlcoholDegree(float $alcoholDegree = null): self
    {
        $this->alcoholDegree = $alcoholDegree;

        return $this;
    }

    public function getTemperature(): ?int
    {
        return $this->temperature;
    }

    public function setTemperature(int $temperature = null): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getBatch(): ?string
    {
        return $this->batch;
    }

    public function setBatch(string $batch = null): self
    {
        $this->batch = $batch;

        return $this;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function setCategory(string $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getBottles(): Collection
    {
        return $this->bottles;
    }

    public function addBottle(Bottle $bottle): self
    {
        if (!$this->bottles->contains($bottle)) {
            $this->bottles[] = $bottle;
            $bottle->setWine($this);
        }

        return $this;
    }

    public function removeBottle(Bottle $bottle): self
    {
        if ($this->bottles->contains($bottle)) {
            $this->bottles->remove($bottle);
            $bottle->setWine(null);
        }

        return $this;
    }

    public function update(self $wine)
    {
        $this->name = $wine->getName();
        $this->designation = $wine->getDesignation();
        $this->varietals = $wine->getVarietals();
        $this->color = $wine->getColor();
        $this->vintage = $wine->getVintage();
        $this->country = $wine->getCountry();
        $this->region = $wine->getRegion();
        $this->winemaker = $wine->getWinemaker();
        $this->rating = $wine->getRating();
        $this->comment = $wine->getComment();
        $this->foodPairing = $wine->getFoodPairing();
        $this->classificationLevel = $wine->getClassificationLevel();
        $this->drinkFrom = $wine->getDrinkFrom();
        $this->drinkTo = $wine->getDrinkTo();
        $this->climaxFrom = $wine->getClimaxFrom();
        $this->climaxTo = $wine->getClimaxTo();
        $this->alcoholDegree = $wine->getAlcoholDegree();
        $this->temperature = $wine->getTemperature();
        $this->batch = $wine->getBatch();
        $this->category = $wine->getCategory();
    }
}
