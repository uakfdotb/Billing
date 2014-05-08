<?php

namespace App\ApiBundle\Security\Authentication\Token;

use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

class ApiKeyToken extends AbstractToken
{
    private $credentials;

    public function __construct($user, $credentials, array $roles = array())
    {
        parent::__construct($roles);

        $this->setUser($user);
        $this->credentials = $credentials;

        parent::setAuthenticated(count($roles) > 0);
    }

    public function setAuthenticated($isAuthenticated)
    {
        if ($isAuthenticated) {
            throw new \LogicException('Cannot set this token to trusted after instantiation.');
        }

        parent::setAuthenticated(false);
    }

    public function getCredentials()
    {
        return $this->credentials;
    }

    public function eraseCredentials()
    {
        parent::eraseCredentials();

        $this->credentials = null;
    }

    public function serialize()
    {
        return serialize(array($this->credentials, parent::serialize()));
    }

    public function unserialize($str)
    {
        list($this->credentials, $parentStr) = unserialize($str);
        parent::unserialize($parentStr);
    }
}
