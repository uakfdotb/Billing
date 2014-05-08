<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ServerGroup
 *
 * @ORM\Table("server_group")
 * @ORM\Entity
 */
class ServerGroup
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="choice_logic", type="integer")
     */
    private $choice_logic;

    /**
     * @var integer
     *
     * @ORM\Column(name="primary_server", type="integer", nullable=true)
     */
    private $primary;

    /**
     * @param int $primary
     */
    public function setPrimary($primary)
    {
        $this->primary = $primary;
    }

    /**
     * @return int
     */
    public function getPrimary()
    {
        return $this->primary;
    }


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
     * Set name
     *
     * @param string $name
     * @return ServerGroup
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
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return ServerGroup
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set choice_logic
     *
     * @param integer $choiceLogic
     * @return ServerGroup
     */
    public function setChoiceLogic($choiceLogic)
    {
        $this->choice_logic = $choiceLogic;

        return $this;
    }

    /**
     * Get choice_logic
     *
     * @return integer 
     */
    public function getChoiceLogic()
    {
        return $this->choice_logic;
    }
}
