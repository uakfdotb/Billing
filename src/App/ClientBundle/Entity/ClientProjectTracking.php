<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientProjectTracking
 *
 * @ORM\Table(name="client_project_tracking")
 * @ORM\Entity
 */
class ClientProjectTracking
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
     * @ORM\Column(name="id_project", type="integer", nullable=false)
     */
    private $idProject;

    /**
     * @var \DateTime $start
     *
     * @ORM\Column(name="start", type="datetime", nullable=false)
     */
    private $start;

    /**
     * @var \DateTime $stop
     *
     * @ORM\Column(name="stop", type="datetime", nullable=true)
     */
    private $stop;

    /**
     * @var integer $staff
     *
     * @ORM\Column(name="staff", type="integer", nullable=false)
     */
    private $staff;

    /**
     * @var float $hourly
     *
     * @ORM\Column(name="hourly", type="decimal", nullable=false)
     */
    private $hourly;

    /**
     * @var boolean $invoiced
     *
     * @ORM\Column(name="invoiced", type="boolean", nullable=false)
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
     * Set idProject
     *
     * @param integer $idProject
     * @return ClientProjectTracking
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
     * Set start
     *
     * @param \DateTime $start
     * @return ClientProjectTracking
     */
    public function setStart($start)
    {
        $this->start = $start;

        return $this;
    }

    /**
     * Get start
     *
     * @return \DateTime
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Set stop
     *
     * @param \DateTime $stop
     * @return ClientProjectTracking
     */
    public function setStop($stop)
    {
        $this->stop = $stop;

        return $this;
    }

    /**
     * Get stop
     *
     * @return \DateTime
     */
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * Set staff
     *
     * @param integer $staff
     * @return ClientProjectTracking
     */
    public function setStaff($staff)
    {
        $this->staff = $staff;

        return $this;
    }

    /**
     * Get staff
     *
     * @return integer
     */
    public function getStaff()
    {
        return $this->staff;
    }

    /**
     * Set hourly
     *
     * @param float $hourly
     * @return ClientProjectTracking
     */
    public function setHourly($hourly)
    {
        $this->hourly = $hourly;

        return $this;
    }

    /**
     * Get hourly
     *
     * @return float
     */
    public function getHourly()
    {
        return $this->hourly;
    }

    /**
     * Set invoiced
     *
     * @param boolean $invoiced
     * @return ClientProjectTracking
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