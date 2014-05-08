<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientInvoicePurchase
 *
 * @ORM\Table(name="client_invoice_purchase")
 * @ORM\Entity
 */
class ClientInvoicePurchase
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
     * @var integer $idInvoice
     *
     * @ORM\Column(name="id_invoice", type="integer", nullable=true)
     */
    private $idInvoice;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var \DateTime $purchaseDate
     *
     * @ORM\Column(name="purchase_date", type="date", nullable=true)
     */
    private $purchaseDate;


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
     * Set idInvoice
     *
     * @param integer $idInvoice
     * @return ClientInvoicePurchase
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

    /**
     * Set name
     *
     * @param string $name
     * @return ClientInvoicePurchase
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set purchaseDate
     *
     * @param \DateTime $purchaseDate
     * @return ClientInvoicePurchase
     */
    public function setPurchaseDate($purchaseDate)
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    /**
     * Get purchaseDate
     *
     * @return \DateTime
     */
    public function getPurchaseDate()
    {
        return $this->purchaseDate;
    }
}