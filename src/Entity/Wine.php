<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="wine")
 */
class Wine
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $designation;

    /**
     * @ORM\Column(type="string")
     */
    private $varietal;

    /**
     * @ORM\Column(type="string")
     */
    private $color;

    /**
     * @ORM\Column(type="integer")
     */
    private $vintage;

    /**
     * @ORM\Column(type="string")
     */
    private $country;

    /**
     * @ORM\Column(type="string")
     */
    private $region;

    /**
     * @ORM\Column(type="string")
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
     * @ORM\Column(type="string", nullable=true)
     */
    private $reference = null;

    /**
     * @ORM\Column(name="classification_level" ,type="string", nullable=true)
     */
    private $classificationLevel = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $aging = null;

    /**
     * @ORM\Column(name="best_aging", type="string", nullable=true)
     */
    private $bestAging = null;

    /**
     * @ORM\Column(name="best_after", type="string", nullable=true)
     */
    private $bestAfter = null;

    /**
     * @ORM\Column(name="drink_after", type="string", nullable=true)
     */
    private $drinkAfter = null;

    /**
     * @ORM\Column(name="alcohol_degree", type="float", nullable=true)
     */
    private $alcoholDegree = null;

    /**
     * @ORM\Column(type="float", nullable=true)
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
     */
    private $bottles;

    public function __construct()
    {
        $this->bottles = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDesignation()
    {
        return $this->designation;
    }

    /**
     * @param mixed $designation
     */
    public function setDesignation($designation)
    {
        $this->designation = $designation;
    }

    /**
     * @return mixed
     */
    public function getVarietal()
    {
        return $this->varietal;
    }

    /**
     * @param mixed $varietal
     */
    public function setVarietal($varietal)
    {
        $this->varietal = $varietal;
    }

    /**
     * @return mixed
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param mixed $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return mixed
     */
    public function getVintage()
    {
        return $this->vintage;
    }

    /**
     * @param mixed $vintage
     */
    public function setVintage($vintage)
    {
        $this->vintage = $vintage;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param mixed $region
     */
    public function setRegion($region)
    {
        $this->region = $region;
    }

    /**
     * @return mixed
     */
    public function getWinemaker()
    {
        return $this->winemaker;
    }

    /**
     * @param mixed $winemaker
     */
    public function setWinemaker($winemaker)
    {
        $this->winemaker = $winemaker;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param mixed $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getFoodPairing()
    {
        return $this->foodPairing;
    }

    /**
     * @param mixed $foodPairing
     */
    public function setFoodPairing($foodPairing)
    {
        $this->foodPairing = $foodPairing;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return mixed
     */
    public function getClassificationLevel()
    {
        return $this->classificationLevel;
    }

    /**
     * @param mixed $classificationLevel
     */
    public function setClassificationLevel($classificationLevel)
    {
        $this->classificationLevel = $classificationLevel;
    }

    /**
     * @return mixed
     */
    public function getAging()
    {
        return $this->aging;
    }

    /**
     * @param mixed $aging
     */
    public function setAging($aging)
    {
        $this->aging = $aging;
    }

    /**
     * @return mixed
     */
    public function getBestAging()
    {
        return $this->bestAging;
    }

    /**
     * @param mixed $bestAging
     */
    public function setBestAging($bestAging)
    {
        $this->bestAging = $bestAging;
    }

    /**
     * @return mixed
     */
    public function getBestAfter()
    {
        return $this->bestAfter;
    }

    /**
     * @param mixed $bestAfter
     */
    public function setBestAfter($bestAfter)
    {
        $this->bestAfter = $bestAfter;
    }

    /**
     * @return mixed
     */
    public function getDrinkAfter()
    {
        return $this->drinkAfter;
    }

    /**
     * @param mixed $drinkAfter
     */
    public function setDrinkAfter($drinkAfter)
    {
        $this->drinkAfter = $drinkAfter;
    }

    /**
     * @return mixed
     */
    public function getAlcoholDegree()
    {
        return $this->alcoholDegree;
    }

    /**
     * @param mixed $alcoholDegree
     */
    public function setAlcoholDegree($alcoholDegree)
    {
        $this->alcoholDegree = $alcoholDegree;
    }

    /**
     * @return mixed
     */
    public function getTemperature()
    {
        return $this->temperature;
    }

    /**
     * @param mixed $temperature
     */
    public function setTemperature($temperature)
    {
        $this->temperature = $temperature;
    }

    /**
     * @return mixed
     */
    public function getBatch()
    {
        return $this->batch;
    }

    /**
     * @param mixed $batch
     */
    public function setBatch($batch)
    {
        $this->batch = $batch;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return ArrayCollection
     */
    public function getBottles()
    {
        return $this->bottles;
    }

    /**
     * @param Bottle $bottle
     */
    public function addBottle(Bottle $bottle)
    {
        if (!$this->bottles->contains($bottle)) {
            $this->bottles[] = $bottle;
            $bottle->setWine($this);
        }
    }

    /**
     * @param Bottle $bottle
     */
    public function removeBottle(Bottle $bottle)
    {
        if ($this->bottles->contains($bottle)) {
            $this->bottles->remove($bottle);
            $bottle->setWine(null);
            // Bottle is orphan -> deleted 'cause of OprhanRemoval
        }
    }
}
