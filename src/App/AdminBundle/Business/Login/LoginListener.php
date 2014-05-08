<?php
namespace App\AdminBundle\Business\Login;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Bundle\DoctrineBundle\Registry as Doctrine;
use App\AdminBundle\Business;
use App\ClientBundle\Entity;

class LoginListener
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function onLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $em   = $this->container->get('doctrine')->getEntityManager();
        $request = $event->getRequest();

        if ($user) {
            $log = new Entity\Log();
            $log->setIdType(Business\Log\Constants::LOG_TYPE_LOGIN_SUCCESS);
            $log->setTimestamp(new \DateTime());
            $log->setDescription('IP: '.$request->getClientIp().' / Email: '.$user->getEmail());

            $em->persist($log);
            $em->flush();
        }
    }
}
