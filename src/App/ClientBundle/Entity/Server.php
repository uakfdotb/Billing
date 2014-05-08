<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Server
 *
 * @ORM\Table("server")
 * @ORM\Entity
 */
class Server
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
     * @var integer
     *
     * @ORM\Column(name="group_id", type="integer", nullable=true)
     */
    private $group_id;

    /**
     * @var string
     *
     * @ORM\Column(name="encrypted_ip", type="text", nullable=true)
     */
    private $encrypted_ip;

    /**
     * @var string
     *
     * @ORM\Column(name="encrypted_user", type="text", nullable=true)
     */
    private $encrypted_user;

    /**
     * @var string
     *
     * @ORM\Column(name="encrypted_pass", type="text", nullable=true)
     */
    private $encrypted_pass;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
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
     * @return Server
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
     * Set group_id
     *
     * @param integer $groupId
     * @return Server
     */
    public function setGroupId($groupId)
    {
        $this->group_id = $groupId;

        return $this;
    }

    /**
     * Get group_id
     *
     * @return integer 
     */
    public function getGroupId()
    {
        return $this->group_id;
    }

    /**
     * Set encrypted_ip
     *
     * @param string $encryptedIp
     * @return Server
     */
    public function setEncryptedIp($encryptedIp)
    {
        $this->encrypted_ip = $encryptedIp;

        return $this;
    }

    /**
     * Get encrypted_ip
     *
     * @return string 
     */
    public function getEncryptedIp()
    {
        return $this->encrypted_ip;
    }

    /**
     * Set encrypted_user
     *
     * @param string $encryptedUser
     * @return Server
     */
    public function setEncryptedUser($encryptedUser)
    {
        $this->encrypted_user = $encryptedUser;

        return $this;
    }

    /**
     * Get encrypted_user
     *
     * @return string 
     */
    public function getEncryptedUser()
    {
        return $this->encrypted_user;
    }

    /**
     * Set encrypted_pass
     *
     * @param string $encryptedPass
     * @return Server
     */
    public function setEncryptedPass($encryptedPass)
    {
        $this->encrypted_pass = $encryptedPass;

        return $this;
    }

    /**
     * Get encrypted_pass
     *
     * @return string 
     */
    public function getEncryptedPass()
    {
        return $this->encrypted_pass;
    }
}
