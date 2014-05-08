<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\Admin
 *
 * @ORM\Table(name="admin")
 * @ORM\Entity
 */
class Admin
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
     * @var string $firstname
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    private $firstname;

    /**
     * @var string $lastname
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    private $lastname;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

    /**
     * @var string $salt
     *
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;

    /**
     * @var float $hourly
     *
     * @ORM\Column(name="hourly", type="decimal", nullable=true)
     */
    private $hourly;

    /**
     * @var string $ip
     *
     * @ORM\Column(name="ip", type="string", length=30, nullable=true)
     */
    private $ip;

    /**
     * @var string $ipend
     *
     * @ORM\Column(name="ipend", type="string", length=30, nullable=true)
     */
    private $ipend;

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
     * Set firstname
     *
     * @param string $firstname
     * @return Admin
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Admin
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Admin
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Admin
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Admin
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set hourly
     *
     * @param float $hourly
     * @return Admin
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
     * Set ip
     *
     * @param string $ip
     * @return Admin
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set ipend
     *
     * @param string $ipend
     * @return Admin
     */
    public function setIpend($ipend)
    {
        $this->ipend = $ipend;

        return $this;
    }

    /**
     * Get ipend
     *
     * @return string
     */
    public function getIpend()
    {
        return $this->ipend;
    }

    /**
     * check if user ip is equal ip in database
     * or in range between ip and ipend
     *
     * @return boolean
     */
    public function loggedWithProperIp($ip)
    {
        $range_start = ip2long($this->ip);
        $range_end   = ip2long($this->ipend);
        $ip          = ip2long($ip);
        if (!$range_end) {
            if ($range_start == $ip) return true;
        } else if ($ip >= $range_start && $ip <= $range_end) {
            return true;
        }
        return false;
    }
}