<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\UserWidget
 *
 * @ORM\Table(name="user_widget")
 * @ORM\Entity
 */
class UserWidget
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
     * @var integer $idUser
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;

    /**
     * @var integer $idWidget
     *
     * @ORM\Column(name="id_widget", type="integer", nullable=false)
     */
    private $idWidget;

    /**
     * @var string $state
     *
     * @ORM\Column(name="state", type="text", nullable=true)
     */
    private $state;

    /**
     * @var integer $sortOrder
     *
     * @ORM\Column(name="sort_order", type="integer", nullable=true)
     */
    private $sortOrder;

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
     * Set idUser
     *
     * @param integer $idUser
     * @return UserWidget
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;
    
        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer 
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set idWidget
     *
     * @param integer $idWidget
     * @return UserWidget
     */
    public function setIdWidget($idWidget)
    {
        $this->idWidget = $idWidget;
    
        return $this;
    }

    /**
     * Get idWidget
     *
     * @return integer 
     */
    public function getIdWidget()
    {
        return $this->idWidget;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return UserWidget
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     * @return UserWidget
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;
    
        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer 
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }
}