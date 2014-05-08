<?php
namespace App\AdminBundle\Business\Login;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use App\AdminBundle\Business\GlobalUtils;

use App\ClientBundle\Entity;

class ClientProvider implements UserProviderInterface
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
            ->getRepository('AppUserBundle:User')
            ->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $username)
            ->getQuery();
        $result = $q->getResult();
        if ($result) {
            foreach ($result as $staff) {
                $userSession = new ClientSession();
                $userSession->setStaff($staff);
                $userSession->setRole('ROLE_CLIENT');

                return $userSession;
            }
        }

        // Search contact
        $q      = $this->doctrine
            ->getRepository('AppClientBundle:ClientContact')
            ->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $username)
            ->getQuery();
        $result = $q->getResult();
        if ($result) {
            foreach ($result as $contact) {
                $client = GlobalUtils::getClientById($this->container, $contact->getIdClient());

                $permissions        = array();
                $contactPermissions = $this->doctrine->getRepository('AppClientBundle:ClientContactPermission')->findByIdClientContact($contact->getId());
                foreach ($contactPermissions as $p) {
                    $permissions[$p->getIdPage()] = 1;
                }

                $userSession = new ClientSession();
                $userSession->setContact($contact);
                $userSession->setPermission($permissions);
                $userSession->setStaff($client);
                $userSession->setRole('ROLE_CLIENT');

                return $userSession;
            }
        }

        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
    }

    public function refreshUser(UserInterface $userSession)
    {
        if (!$userSession instanceof ClientSession) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($userSession)));
        }

        return $this->loadUserByUsername($userSession->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'App\AdminBundle\Business\Login\ClientSession';
    }
}
