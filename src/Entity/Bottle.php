<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
* @ORM\Entity()
* @ORM\Table(name="bottle")
*/
class Bottle
{
  /**
   * @ORM\Column(type="integer")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $id;

  /**
   * @ORM\ManyToOne(targetEntity="Wine", inversedBy="bottles", cascade={"persist"})
   * @ORM\JoinColumn(name="wine_id", referencedColumnName="id", onDelete="CASCADE", nullable=false)
   */
  private $wine;

  /**
   * @ORM\Column(name="acquisition_price", type="float", nullable=true)
   */
  private $acquisitionPrice = null;

  /**
   * @ORM\Column(name="estimation_price", type="float", nullable=true)
   */
  private $estimationPrice = null;

  /**
   * @ORM\Column(type="string")
   */
  private $volume = '75cl';

  /**
   * @ORM\Column(name="storage_location", type="string", nullable=true)
   */
  private $storageLocation = null;

  /**
   * @return mixed
   */
  public function getId()
  {
      return $this->id;
  }

  /**
   * @return Wine
   */
  public function getWine()
  {
      return $this->wine;
  }

  /**
   * @param Wine $wine
   */
  public function setWine(Wine $wine)
  {
      $this->wine = $wine;
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
  public function setAcquisitionPrice(float $acquisitionPrice)
  {
      $this->acquisitionPrice = $acquisitionPrice;
  }

  /**
   * @return mixed
   */
  public function getEstimationPrice()
  {
      return $this->estimationPrice;
  }

  /**
   * @param mixed $estimationPrice
   */
  public function setEstimationPrice(float $estimationPrice)
  {
      $this->estimationPrice = $estimationPrice;
  }

  /**
   * @return mixed
   */
  public function getVolume()
  {
      return $this->volume;
  }

  /**
   * @param mixed $volume
   */
  public function setVolume(string $volume)
  {
      $this->volume = $volume;
  }
  /**
   * @return mixed
   */
  public function getStorageLocation()
  {
      return $this->storageLocation;
  }

  /**
   * @param mixed $storageLocation
   */
  public function setStorageLocation(string $storageLocation)
  {
      $this->storageLocation = $storageLocation;
  }



} ?>
