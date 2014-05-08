<?php
namespace App\AdminBundle\Business\Login;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use App\ClientBundle\Entity;

class StaffProvider implements UserProviderInterface
{
    protected $doctrine;
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
        $this->doctrine  = $container->get('doctrine');
    }

    public function loadUserByUsername($username)
    {
        $q      = $this->doctrine
            ->getRepository('AppClientBundle:Admin')
            ->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $username)
            ->getQuery();
        $result = $q->getResult();
        foreach ($result as $staff) {
            $userSession = new UserSession();
            $userSession->setStaff($staff);
            $userSession->setRole('ROLE_ADMIN');
            return $userSession;
        }

        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
    }

    public function refreshUser(UserInterface $userSession)
    {
        if (!$userSession instanceof UserSession) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($userSession)));
        }

        return $this->loadUserByUsername($userSession->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'App\AdminBundle\Business\Login\UserSession';
    }
}
