<?php

namespace App\UserBundle\Security;

use FOS\UserBundle\Security\UserProvider as FosUserProvider;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;

class UserProvider extends FosUserProvider
{
    public $container;

    public function __construct(UserManagerInterface $userManager, $container) 
    {
        parent::__construct($userManager);
        $this->container = $container;
    }

    public function loadUserByUsername($username)
    {
        $user = $this->findUser($username);

        if (!$user)
        {
            throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
        }

        // Check if admin user is accessing its own tenant domain

        if ($this->container->get('kernel')->helper->isAdmin())
        {
            $tenant = $this->container->get('app_admin.helper.common')->getCurrentTenant();

            $userTenant = $user->getTenant();

            if (empty($tenant) || empty($userTenant) || ($userTenant->getId() != $tenant->getId()))
            {
                throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
            }
        }

        return $user;
    }
}