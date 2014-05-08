<?php

namespace App\ClientBundle\Helper;

use App\ClientBundle\Entity;
use App\AdminBundle\Business as AdminBusiness;

class ClientLog
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function log($idType, $description = '')
    {
        $userSession = $this->container->get('app_admin.helper.user')->getUserSession();
        if (!empty($userSession) && $userSession instanceof AdminBusiness\Login\ClientSession) {
            $em  = $this->container->get('doctrine')->getEntityManager();
            $log = new Entity\ClientLog();
            $log->setIdClient($userSession->getId());
            $log->setIdType($idType);
            $log->setTimestamp(new \DateTime());
            $log->setDescription($description);
            $em->persist($log);
            $em->flush();
        }
    }
}
