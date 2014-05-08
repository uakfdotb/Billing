<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientInvoiceItem
 *
 * @ORM\Table(name="client_invoice_item")
 * @ORM\Entity
 */
class ClientInvoiceItem
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
     * @ORM\Column(name="quantity", type="float", nullable=false, precision=11, scale=2)
     */
    private $quantity;

    /**
     * @var float $unitPrice
     *
     * @ORM\Column(name="unit_price", type="decimal", nullable=false, precision=11, scale=2)
     */
    private $unitPrice;

    /**
     * @var integer $idInvoice
     *
     * @ORM\Column(name="id_invoice", type="integer", nullable=false)
     */
    private $idInvoice;


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
     * @return ClientInvoiceItem
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
     * @return ClientInvoiceItem
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
     * @return ClientInvoiceItem
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
     * @return ClientInvoiceItem
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
     * Set idInvoice
     *
     * @param integer $idInvoice
     * @return ClientInvoiceItem
     */
    public function setIdInvoice($idInvoice)
    {
        $this->idInvoice = $idInvoice;

        return $this;
    }

    /**
     * Get idInvoice
     *
     * @return integer
     */
    public function getIdInvoice()
    {
        return $this->idInvoice;
    }
}
