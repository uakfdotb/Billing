<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientRecurringItem
 *
 * @ORM\Table(name="client_recurring_item")
 * @ORM\Entity
 */
class ClientRecurringItem
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
     * @ORM\Column(name="id_type", type="integer", nullable=true)
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
     * @ORM\Column(name="unit_price", type="decimal", nullable=false, precision=11, scale=2)
     */
    private $unitPrice;

    /**
     * @var integer $idRecurring
     *
     * @ORM\Column(name="id_recurring", type="integer", nullable=false)
     */
    private $idRecurring;


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
     * @return ClientRecurringItem
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
     * @return ClientRecurringItem
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
     * @return ClientRecurringItem
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
     * @return ClientRecurringItem
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
     * Set idRecurring
     *
     * @param integer $idRecurring
     * @return ClientRecurringItem
     */
    public function setIdRecurring($idRecurring)
    {
        $this->idRecurring = $idRecurring;

        return $this;
    }

    /**
     * Get idRecurring
     *
     * @return integer
     */
    public function getIdRecurring()
    {
        return $this->idRecurring;
    }
}