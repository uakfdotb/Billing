<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ClientContactPermission
 *
 * @ORM\Table(name="client_contact_permission")
 * @ORM\Entity
 */
class ClientContactPermission
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
     * @var integer $idClientContact
     *
     * @ORM\Column(name="id_client_contact", type="integer", nullable=true)
     */
    private $idClientContact;

    /**
     * @var integer $idPage
     *
     * @ORM\Column(name="id_page", type="integer", nullable=true)
     */
    private $idPage;


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
     * Set idClientContact
     *
     * @param integer $idClientContact
     * @return ClientContactPermission
     */
    public function setIdClientContact($idClientContact)
    {
        $this->idClientContact = $idClientContact;

        return $this;
    }

    /**
     * Get idClientContact
     *
     * @return integer
     */
    public function getIdClientContact()
    {
        return $this->idClientContact;
    }

    /**
     * Set idPage
     *
     * @param integer $idPage
     * @return ClientContactPermission
     */
    public function setIdPage($idPage)
    {
        $this->idPage = $idPage;

        return $this;
    }

    /**
     * Get idPage
     *
     * @return integer
     */
    public function getIdPage()
    {
        return $this->idPage;
    }
}