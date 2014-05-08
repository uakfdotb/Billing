<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientProjectTask
 *
 * @ORM\Table(name="client_project_task")
 * @ORM\Entity
 */
class ClientProjectTask
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
     * @var integer $idProject
     *
     * @ORM\Column(name="id_project", type="integer", nullable=true)
     */
    private $idProject;

    /**
     * @var integer $idWorkType
     *
     * @ORM\Column(name="id_work_type", type="integer", nullable=true)
     */
    private $idWorkType;

    /**
     * @var string $subject
     *
     * @ORM\Column(name="subject", type="string", length=255, nullable=true)
     */
    private $subject;

    /**
     * @var \DateTime $timestamp
     *
     * @ORM\Column(name="timestamp", type="datetime", nullable=true)
     */
    private $timestamp;

    /**
     * @var float $quantity
     *
     * @ORM\Column(name="quantity", type="decimal", nullable=true)
     */
    private $quantity;

    /**
     * @var integer $unit
     *
     * @ORM\Column(name="unit", type="integer", nullable=true)
     */
    private $unit;

    /**
     * @var float $unitPrice
     *
     * @ORM\Column(name="unit_price", type="decimal", nullable=true)
     */
    private $unitPrice;

    /**
     * @var boolean $isBillable
     *
     * @ORM\Column(name="is_billable", type="boolean", nullable=true)
     */
    private $isBillable;

    /**
     * @var boolean $invoiced
     *
     * @ORM\Column(name="invoiced", type="boolean", nullable=true)
     */
    private $invoiced;

    /**
     * @var integer $status
     *
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    private $status;


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
     * Set idProject
     *
     * @param integer $idProject
     * @return ClientProjectTask
     */
    public function setIdProject($idProject)
    {
        $this->idProject = $idProject;

        return $this;
    }

    /**
     * Get idProject
     *
     * @return integer
     */
    public function getIdProject()
    {
        return $this->idProject;
    }

    /**
     * Set idWorkType
     *
     * @param integer $idWorkType
     * @return ClientProjectTask
     */
    public function setIdWorkType($idWorkType)
    {
        $this->idWorkType = $idWorkType;

        return $this;
    }

    /**
     * Get idWorkType
     *
     * @return integer
     */
    public function getIdWorkType()
    {
        return $this->idWorkType;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return ClientProjectTask
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     * @return ClientProjectTask
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
     * Set quantity
     *
     * @param float $quantity
     * @return ClientProjectTask
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
     * Set unit
     *
     * @param integer $unit
     * @return ClientProjectTask
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;

        return $this;
    }

    /**
     * Get unit
     *
     * @return integer
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * Set unitPrice
     *
     * @param float $unitPrice
     * @return ClientProjectTask
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
     * Set isBillable
     *
     * @param boolean $isBillable
     * @return ClientProjectTask
     */
    public function setIsBillable($isBillable)
    {
        $this->isBillable = $isBillable;

        return $this;
    }

    /**
     * Get isBillable
     *
     * @return boolean
     */
    public function getIsBillable()
    {
        return $this->isBillable;
    }

    /**
     * Set invoiced
     *
     * @param boolean $invoiced
     * @return ClientProjectTask
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

    /**
     * Set status
     *
     * @param integer $status
     * @return ClientProjectTask
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }
}