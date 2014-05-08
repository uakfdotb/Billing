<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientEstimateItem
 *
 * @ORM\Table(name="client_estimate_item")
 * @ORM\Entity
 */
class ClientEstimateItem
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer $idType
     *
     * @ORM\Column(name="id_type", type="integer", nullable=false)
     */
    private $idType;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var float $quantity
     *
     * @ORM\Column(name="quantity", type="float", nullable=false)
     */
    private $quantity;

    /**
     * @var float $unitPrice
     *
     * @ORM\Column(name="unit_price", type="decimal", nullable=false)
     */
    private $unitPrice;

    /**
     * @var integer $idEstimate
     *
     * @ORM\Column(name="id_estimate", type="integer", nullable=false)
     */
    private $idEstimate;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idType
     *
     * @param integer $idType
     * @return ClientEstimateItem
     */
    public function setIdType($idType)
    {
        $this->idType = $idType;

        return $this;
    }

    /**
     * Get idType
     *
     * @return integer
     */
    public function getIdType()
    {
        return $this->idType;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return ClientEstimateItem
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set quantity
     *
     * @param float $quantity
     * @return ClientEstimateItem
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set unitPrice
     *
     * @param float $unitPrice
     * @return ClientEstimateItem
     */
    public function setUnitPrice($unitPrice)
    {
        $this->unitPrice = $unitPrice;

        return $this;
    }

    /**
     * Get unitPrice
     *
     * @return float
     */
    public function getUnitPrice()
    {
        return $this->unitPrice;
    }

    /**
     * Set idEstimate
     *
     * @param integer $idEstimate
     * @return ClientEstimateItem
     */
    public function setIdEstimate($idEstimate)
    {
        $this->idEstimate = $idEstimate;

        return $this;
    }

    /**
     * Get idEstimate
     *
     * @return integer
     */
    public function getIdEstimate()
    {
        return $this->idEstimate;
    }
}