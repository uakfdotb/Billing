<?php

namespace App\AdminBundle\Business\Server;

use App\AdminBundle\Business\Order\Constants as OrderConstants;
use App\AdminBundle\Business\ServerGroup\Constants as ServerGroupConstants;


class Utils
{

    public static function countAccountsOnServer($container, $serverId)
    {
        $repository = $container->get('doctrine')->getRepository('AppClientBundle:ClientProduct');
        //$activeProducts = $repository->findBy(['idServer' => $serverId, 'status' => OrderConstants::ORDER_STATUS_ACTIVE]);
        $activeProducts = $repository->findBy(['idServer' => $serverId]); // Fix me
        return count($activeProducts);
    }
    public static function getServerType($container, $groupId)
    {
        $group = $container->get('doctrine')->getRepository('AppClientBundle:ServerGroup')->findOneById($groupId);
        if (empty($group)) return '';
        return ServerGroupConstants::getServerGroupTypes()[$group->getType()];
    }
    public static function getServerGroup($container, $groupId)
    {
        $group = $container->get('doctrine')->getRepository('AppClientBundle:ServerGroup')->findOneById($groupId);
        return empty($group) ? '' : $group->getName();
    }
}
