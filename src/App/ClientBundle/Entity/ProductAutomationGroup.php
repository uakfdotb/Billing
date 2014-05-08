<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\ProductAutomationGroup
 *
 * @ORM\Table(name="product_automation_group")
 * @ORM\Entity
 */
class ProductAutomationGroup
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
     * @var integer $idProduct
     *
     * @ORM\Column(name="id_product", type="integer", nullable=true)
     */
    private $idProduct;

    /**
     * @var integer $idAutomationGroup
     *
     * @ORM\Column(name="id_automation_group", type="integer", nullable=true)
     */
    private $idAutomationGroup;

    /**
     * @var integer $idEvent
     *
     * @ORM\Column(name="id_event", type="integer", nullable=true)
     */
    private $idEvent;


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
     * Set idProduct
     *
     * @param integer $idProduct
     * @return ProductAutomationGroup
     */
    public function setIdProduct($idProduct)
    {
        $this->idProduct = $idProduct;

        return $this;
    }

    /**
     * Get idProduct
     *
     * @return integer
     */
    public function getIdProduct()
    {
        return $this->idProduct;
    }

    /**
     * Set idAutomationGroup
     *
     * @param integer $idAutomationGroup
     * @return ProductAutomationGroup
     */
    public function setIdAutomationGroup($idAutomationGroup)
    {
        $this->idAutomationGroup = $idAutomationGroup;

        return $this;
    }

    /**
     * Get idAutomationGroup
     *
     * @return integer
     */
    public function getIdAutomationGroup()
    {
        return $this->idAutomationGroup;
    }

    /**
     * Set idEvent
     *
     * @param integer $idEvent
     * @return ProductAutomationGroup
     */
    public function setIdEvent($idEvent)
    {
        $this->idEvent = $idEvent;

        return $this;
    }

    /**
     * Get idEvent
     *
     * @return integer
     */
    public function getIdEvent()
    {
        return $this->idEvent;
    }
}