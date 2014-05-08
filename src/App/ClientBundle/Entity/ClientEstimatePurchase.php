<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientEstimatePurchase
 *
 * @ORM\Table(name="client_estimate_purchase")
 * @ORM\Entity
 */
class ClientEstimatePurchase
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
     * @var integer $idEstimate
     *
     * @ORM\Column(name="id_estimate", type="integer", nullable=true)
     */
    private $idEstimate;

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
     * Set idEstimate
     *
     * @param integer $idEstimate
     * @return ClientEstimatePurchase
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

    /**
     * Set name
     *
     * @param string $name
     * @return ClientEstimatePurchase
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
     * @return ClientEstimatePurchase
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