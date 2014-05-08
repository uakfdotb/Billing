<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\AccountTransaction
 *
 * @ORM\Table(name="account_transaction")
 * @ORM\Entity
 */
class AccountTransaction
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
     * @var integer $idAccount
     *
     * @ORM\Column(name="id_account", type="integer", nullable=true)
     */
    private $idAccount;

    /**
     * @var integer $idDirection
     *
     * @ORM\Column(name="id_direction", type="integer", nullable=true)
     */
    private $idDirection;

    /**
     * @var \DateTime $timestamp
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=true)
     */
    private $timestamp;

    /**
     * @var float $amount
     *
     * @ORM\Column(name="amount", type="decimal", nullable=true)
     */
    private $amount;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;


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
     * Set idAccount
     *
     * @param integer $idAccount
     * @return AccountTransaction
     */
    public function setIdAccount($idAccount)
    {
        $this->idAccount = $idAccount;

        return $this;
    }

    /**
     * Get idAccount
     *
     * @return integer
     */
    public function getIdAccount()
    {
        return $this->idAccount;
    }

    /**
     * Set idDirection
     *
     * @param integer $idDirection
     * @return AccountTransaction
     */
    public function setIdDirection($idDirection)
    {
        $this->idDirection = $idDirection;

        return $this;
    }

    /**
     * Get idDirection
     *
     * @return integer
     */
    public function getIdDirection()
    {
        return $this->idDirection;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return AccountTransaction
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return AccountTransaction
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

    /**
     * Set description
     *
     * @param string $description
     * @return AccountTransaction
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
}