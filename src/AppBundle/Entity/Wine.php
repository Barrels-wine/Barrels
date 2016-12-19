<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Wine
 * @package AppBundle\Entity
 *
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
    private $appelation;

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
     * @ORM\Column(name="acquisition_price", type="float", nullable=true)
     */
    private $acquisitionPrice = null;

    /**
     * @ORM\Column(name="estimation_price", type="float", nullable=true)
     */
    private $estimationPrice = null;

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
     * @ORM\Column(name="classifcation_level" ,type="string", nullable=true)
     */
    private $classificationLevel = null;

    /**
     * @ORM\Column(type="string")
     */
    private $volume = '75cl';

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $reference = null;

    /**
     * @ORM\Column(type="interval", nullable=true)
     */
    private $aging = null;

    /**
     * @ORM\Column(name="best_aging", type="interval", nullable=true)
     */
    private $bestAging = null;

    /**
     * @ORM\Column(name="best_after", type="interval", nullable=true)
     */
    private $bestAfter = null;

    /**
     * @ORM\Column(name="drink_after", type="interval", nullable=true)
     */
    private $drinkAfter = null;

    /**
     * @ORM\Column(name="alcohol_degree", type="float", nullable=true)
     */
    private $alcoholDegree = null;

    /**
     * @ORM\Column(type="interval", nullable=true)
     */
    private $temperature = null;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $batch = null;

    /**
     * @ORM\Column(name="nb_bottles", type="integer")
     */
    private $nbBottles = 0;

    /**
     * @ORM\Column(name="storage_location", type="string", nullable=true)
     */
    private $storageLocation = null;

    /**
     * @ORM\Column(type="string")
     */
    private $category;

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
    public function getAppelation()
    {
        return $this->appelation;
    }

    /**
     * @param mixed $appelation
     */
    public function setAppelation($appelation)
    {
        $this->appelation = $appelation;
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
    public function getAcquisitionPrice()
    {
        return $this->acquisitionPrice;
    }

    /**
     * @param mixed $acquisitionPrice
     */
    public function setAcquisitionPrice($acquisitionPrice)
    {
        $this->acquisitionPrice = $acquisitionPrice;
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
}
