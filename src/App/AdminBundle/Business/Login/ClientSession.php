<?php

namespace App\AdminBundle\Business\Login;


use Symfony\Component\Security\Core\User\UserInterface;


class ClientSession implements UserInterface
{

    private $staff;

    private $role;


    public function setRole($role)
    {
        $this->role = $role;
    }

    public function setStaff($staff)
    {
        $this->staff = $staff;
    }

    public function getStaff()
    {
        return $this->staff;
    }


    private $contact = null;

    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function isPrimary()
    {
        return $this->contact == null;
    }

    private $permissions = array();

    public function setPermission($permissions)
    {
        $this->permissions = $permissions;
    }

    public function getPermission()
    {
        return $this->permissions;
    }

    public function hasPermission($idPage)
    {
        if ($this->isPrimary()) {
            return true;
        }

        return isset($this->permissions[$idPage]);
    }

    function getPassword()
    {

        return $this->staff->getPassword();

    }


    function getSalt()
    {

        return 'abc';

    }


    function getUsername()
    {
        if ($this->contact) {
            return $this->contact->getEmail();
        }
        return $this->staff->getEmail();

    }


    function getRoles()
    {

        return array($this->role);

    }


    function eraseCredentials()
    {


    }

    function equals(UserInterface $user)
    {

        return $this->getEmail() === $user->getEmail();

    }

}