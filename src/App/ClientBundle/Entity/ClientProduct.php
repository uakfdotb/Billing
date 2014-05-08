<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientProduct
 *
 * @ORM\Table("client_product")
 * @ORM\Entity
 */
class ClientProduct
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_payment_term", type="integer")
     */
    private $idPaymentTerm;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=255)
     */
    private $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="encrypted_username", type="string", length=255)
     */
    private $encryptedUsername;

    /**
     * @var string
     *
     * @ORM\Column(name="encrypted_password", type="string", length=255)
     */
    private $encryptedPassword;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_server", type="integer")
     */
    private $idServer;

    /**
     * @var string
     *
     * @ORM\Column(name="cost", type="decimal", scale=2, precision=11)
     */
    private $cost;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_schedule", type="integer")
     */
    private $idSchedule;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="next_due", type="datetime")
     */
    private $nextDue;

    /**
     * @var integer
     *
     * @ORM\Column(name="tax_group", type="integer")
     */
    private $taxGroup;

    /**
     * @var boolean
     *
     * @ORM\Column(name="reminders", type="boolean")
     */
    private $reminders;

    /**
     * @var boolean
     *
     * @ORM\Column(name="overdue_notices", type="boolean")
     */
    private $overdueNotices;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_product", type="integer")
     */
    private $idProduct;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_client", type="integer")
     */
    private $idClient;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=255)
     */
    private $domain;

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
     * Set idPaymentTerm
     *
     * @param integer $idPaymentTerm
     * @return ClientProduct
     */
    public function setIdPaymentTerm($idPaymentTerm)
    {
        $this->idPaymentTerm = $idPaymentTerm;

        return $this;
    }

    /**
     * Get idPaymentTerm
     *
     * @return integer 
     */
    public function getIdPaymentTerm()
    {
        return $this->idPaymentTerm;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return ClientProduct
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string 
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set encryptedUsername
     *
     * @param string $encryptedUsername
     * @return ClientProduct
     */
    public function setEncryptedUsername($encryptedUsername)
    {
        $this->encryptedUsername = $encryptedUsername;

        return $this;
    }

    /**
     * Get encryptedUsername
     *
     * @return string 
     */
    public function getEncryptedUsername()
    {
        return $this->encryptedUsername;
    }

    /**
     * Set encryptedPassword
     *
     * @param string $encryptedPassword
     * @return ClientProduct
     */
    public function setEncryptedPassword($encryptedPassword)
    {
        $this->encryptedPassword = $encryptedPassword;

        return $this;
    }

    /**
     * Get encryptedPassword
     *
     * @return string 
     */
    public function getEncryptedPassword()
    {
        return $this->encryptedPassword;
    }

    /**
     * Set idServer
     *
     * @param integer $idServer
     * @return ClientProduct
     */
    public function setIdServer($idServer)
    {
        $this->idServer = $idServer;

        return $this;
    }

    /**
     * Get idServer
     *
     * @return integer 
     */
    public function getIdServer()
    {
        return $this->idServer;
    }

    /**
     * Set cost
     *
     * @param string $cost
     * @return ClientProduct
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return string 
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set idSchedule
     *
     * @param integer $idSchedule
     * @return ClientProduct
     */
    public function setIdSchedule($idSchedule)
    {
        $this->idSchedule = $idSchedule;

        return $this;
    }

    /**
     * Get idSchedule
     *
     * @return integer 
     */
    public function getIdSchedule()
    {
        return $this->idSchedule;
    }

    /**
     * Set nextDue
     *
     * @param \DateTime $nextDue
     * @return ClientProduct
     */
    public function setNextDue($nextDue)
    {
        $this->nextDue = $nextDue;

        return $this;
    }

    /**
     * Get nextDue
     *
     * @return \DateTime 
     */
    public function getNextDue()
    {
        return $this->nextDue;
    }

    /**
     * Set taxGroup
     *
     * @param integer $taxGroup
     * @return ClientProduct
     */
    public function setTaxGroup($taxGroup)
    {
        $this->taxGroup = $taxGroup;

        return $this;
    }

    /**
     * Get taxGroup
     *
     * @return integer 
     */
    public function getTaxGroup()
    {
        return $this->taxGroup;
    }

    /**
     * Set reminders
     *
     * @param boolean $reminders
     * @return ClientProduct
     */
    public function setReminders($reminders)
    {
        $this->reminders = $reminders;

        return $this;
    }

    /**
     * Get reminders
     *
     * @return boolean 
     */
    public function getReminders()
    {
        return $this->reminders;
    }

    /**
     * Set overdueNotices
     *
     * @param boolean $overdueNotices
     * @return ClientProduct
     */
    public function setOverdueNotices($overdueNotices)
    {
        $this->overdueNotices = $overdueNotices;

        return $this;
    }

    /**
     * Get overdueNotices
     *
     * @return boolean 
     */
    public function getOverdueNotices()
    {
        return $this->overdueNotices;
    }

    /**
     * @param int $idProduct
     */
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;
    }

    /**
     * @return int
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * @param int $idClient
     */
    public function setIdClient($idClient)
    {
        $this->idClient = $idClient;
    }

    /**
     * @return int
     */
    public function getIdClient()
    {
        return $this->idClient;
    }

    /**
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $domain
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }
}
