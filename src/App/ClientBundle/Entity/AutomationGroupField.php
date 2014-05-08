<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\AutomationGroupField
 *
 * @ORM\Table(name="automation_group_field")
 * @ORM\Entity
 */
class AutomationGroupField
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
     * @var integer $idAutomationGroup
     *
     * @ORM\Column(name="id_automation_group", type="integer", nullable=true)
     */
    private $idAutomationGroup;

    /**
     * @var integer $idProductField
     *
     * @ORM\Column(name="id_product_field", type="integer", nullable=true)
     */
    private $idProductField;


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
     * Set idAutomationGroup
     *
     * @param integer $idAutomationGroup
     * @return AutomationGroupField
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
     * Set idProductField
     *
     * @param integer $idProductField
     * @return AutomationGroupField
     */
    public function setIdProductField($idProductField)
    {
        $this->idProductField = $idProductField;

        return $this;
    }

    /**
     * Get idProductField
     *
     * @return integer
     */
    public function getIdProductField()
    {
        return $this->idProductField;
    }
}