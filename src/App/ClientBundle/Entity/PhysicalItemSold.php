<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\PhysicalItemSold
 *
 * @ORM\Table(name="physical_item_sold")
 * @ORM\Entity
 */
class PhysicalItemSold
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
     * @var integer $idClient
     *
     * @ORM\Column(name="id_client", type="integer", nullable=true)
     */
    private $idClient;

    /**
     * @var \DateTime $dateOut
     *
     * @ORM\Column(name="date_out", type="datetime", nullable=true)
     */
    private $dateOut;

    /**
     * @var float $sellPrice
     *
     * @ORM\Column(name="sell_price", type="decimal", nullable=true)
     */
    private $sellPrice;

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
     * @var boolean $invoiced
     *
     * @ORM\Column(name="invoiced", type="boolean", nullable=true)
     */
    private $invoiced;


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
     * @return PhysicalItemSold
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
     * Set idClient
     *
     * @param integer $idClient
     * @return PhysicalItemSold
     */
    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;

        return $this;
    }

    /**
     * Get idClient
     *
     * @return integer
     */
    public function getIdClient()
    {
        return $this->idClient;
    }

    /**
     * Set dateOut
     *
     * @param \DateTime $dateOut
     * @return PhysicalItemSold
     */
    public function setDateOut($dateOut)
    {
        $this->dateOut = $dateOut;

        return $this;
    }

    /**
     * Get dateOut
     *
     * @return \DateTime
     */
    public function getDateOut()
    {
        return $this->dateOut;
    }

    /**
     * Set sellPrice
     *
     * @param float $sellPrice
     * @return PhysicalItemSold
     */
    public function setSellPrice($sellPrice)
    {
        $this->sellPrice = $sellPrice;

        return $this;
    }

    /**
     * Get sellPrice
     *
     * @return float
     */
    public function getSellPrice()
    {
        return $this->sellPrice;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return PhysicalItemSold
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
     * @return PhysicalItemSold
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
     * Set invoiced
     *
     * @param boolean $invoiced
     * @return PhysicalItemSold
     */
    public function setInvoiced($invoiced)
    {
        $this->invoiced = $invoiced;

        return $this;
    }

    /**
     * Get invoiced
     *
     * @return boolean
     */
    public function getInvoiced()
    {
        return $this->invoiced;
    }
}