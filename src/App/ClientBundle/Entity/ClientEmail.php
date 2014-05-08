<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClientEmail
 *
 * @ORM\Table("client_email")
 * @ORM\Entity
 */
class ClientEmail
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
     * @ORM\Column(name="id_client", type="integer")
     */
    private $id_client;

    /**
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     */
    private $subject;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime")
     */
    private $timestamp;


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
     * Set id_client
     *
     * @param integer $idClient
     * @return ClientEmail
     */
    public function setIdClient($idClient)
    {
        $this->id_client = $idClient;

        return $this;
    }

    /**
     * Get id_client
     *
     * @return integer 
     */
    public function getIdClient()
    {
        return $this->id_client;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return ClientEmail
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
     * @return ClientEmail
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
}
