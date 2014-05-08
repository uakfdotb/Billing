<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\PhysicalItemPurchase
 *
 * @ORM\Table(name="physical_item_purchase")
 * @ORM\Entity
 */
class PhysicalItemPurchase
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
     * @var integer $idPhysicalItem
     *
     * @ORM\Column(name="id_physical_item", type="integer", nullable=true)
     */
    private $idPhysicalItem;

    /**
     * @var integer $idSupplier
     *
     * @ORM\Column(name="id_supplier", type="integer", nullable=true)
     */
    private $idSupplier;

    /**
     * @var \DateTime $dateIn
     *
     * @ORM\Column(name="date_in", type="datetime", nullable=true)
     */
    private $dateIn;

    /**
     * @var float $purchasePrice
     *
     * @ORM\Column(name="purchase_price", type="decimal", nullable=true)
     */
    private $purchasePrice;

    /**
     * @var integer $quantity
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var string $serialNumber
     *
     * @ORM\Column(name="serial_number", type="string", length=255, nullable=true)
     */
    private $serialNumber;

    /**
     * @var boolean $isPurchased
     *
     * @ORM\Column(name="is_purchased", type="boolean", nullable=true)
     */
    private $isPurchased;


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
     * Set idPhysicalItem
     *
     * @param integer $idPhysicalItem
     * @return PhysicalItemPurchase
     */
    public function setIdPhysicalItem($idPhysicalItem)
    {
        $this->idPhysicalItem = $idPhysicalItem;

        return $this;
    }

    /**
     * Get idPhysicalItem
     *
     * @return integer
     */
    public function getIdPhysicalItem()
    {
        return $this->idPhysicalItem;
    }

    /**
     * Set idSupplier
     *
     * @param integer $idSupplier
     * @return PhysicalItemPurchase
     */
    public function setIdSupplier($idSupplier)
    {
        $this->idSupplier = $idSupplier;

        return $this;
    }

    /**
     * Get idSupplier
     *
     * @return integer
     */
    public function getIdSupplier()
    {
        return $this->idSupplier;
    }

    /**
     * Set dateIn
     *
     * @param \DateTime $dateIn
     * @return PhysicalItemPurchase
     */
    public function setDateIn($dateIn)
    {
        $this->dateIn = $dateIn;

        return $this;
    }

    /**
     * Get dateIn
     *
     * @return \DateTime
     */
    public function getDateIn()
    {
        return $this->dateIn;
    }

    /**
     * Set purchasePrice
     *
     * @param float $purchasePrice
     * @return PhysicalItemPurchase
     */
    public function setPurchasePrice($purchasePrice)
    {
        $this->purchasePrice = $purchasePrice;

        return $this;
    }

    /**
     * Get purchasePrice
     *
     * @return float
     */
    public function getPurchasePrice()
    {
        return $this->purchasePrice;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return PhysicalItemPurchase
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set serialNumber
     *
     * @param string $serialNumber
     * @return PhysicalItemPurchase
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    /**
     * Get serialNumber
     *
     * @return string
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * Set isPurchased
     *
     * @param boolean $isPurchased
     * @return PhysicalItemPurchase
     */
    public function setIsPurchased($isPurchased)
    {
        $this->isPurchased = $isPurchased;

        return $this;
    }

    /**
     * Get isPurchased
     *
     * @return boolean
     */
    public function getIsPurchased()
    {
        return $this->isPurchased;
    }
}