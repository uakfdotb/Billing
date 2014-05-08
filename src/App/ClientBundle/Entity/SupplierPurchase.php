<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\SupplierPurchase
 *
 * @ORM\Table(name="supplier_purchase")
 * @ORM\Entity
 */
class SupplierPurchase
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
     * @var integer $idSupplier
     *
     * @ORM\Column(name="id_supplier", type="integer", nullable=true)
     */
    private $idSupplier;

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
     * @var integer $idAccountTransaction
     *
     * @ORM\Column(name="id_account_transaction", type="integer", nullable=true)
     */
    private $idAccountTransaction;

    /**
     * @var float $amount
     *
     * @ORM\Column(name="amount", type="decimal", nullable=true, precision=11, scale=2)
     */
    private $amount;

    /**
     * @var integer $nominalCode
     *
     * @ORM\Column(name="nominal_code", type="integer", nullable=true)
     */
    private $nominalCode;

    /**
     * @param int $nominalCode
     */
    public function setNominalCode($nominalCode)
    {
        $this->nominalCode = $nominalCode;
    }

    /**
     * @return int
     */
    public function getNominalCode()
    {
        return $this->nominalCode;
    }


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
     * Set idSupplier
     *
     * @param integer $idSupplier
     * @return SupplierPurchase
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
     * Set name
     *
     * @param string $name
     * @return SupplierPurchase
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
     * @return SupplierPurchase
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

    /**
     * Set idAccountTransaction
     *
     * @param integer $idAccountTransaction
     * @return SupplierPurchase
     */
    public function setIdAccountTransaction($idAccountTransaction)
    {
        $this->idAccountTransaction = $idAccountTransaction;

        return $this;
    }

    /**
     * Get idAccountTransaction
     *
     * @return integer
     */
    public function getIdAccountTransaction()
    {
        return $this->idAccountTransaction;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return SupplierPurchase
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }
}