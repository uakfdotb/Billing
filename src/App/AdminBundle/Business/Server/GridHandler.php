<?php
namespace App\AdminBundle\Business\Server;

use App\AdminBundle\Business\Base\BaseGridHandler;

class GridHandler extends BaseGridHandler
{
    function buildBaseQuery($query, $baseObject = 'p', $filter)
    {
        $query->select($baseObject)
            ->from('AppClientBundle:Server', $baseObject);

        // Build filter here - Consult arrayToSQL($filter, false)
        $this->container->get('app_admin.helper.kendo_grid')->filter($query, $baseObject, $filter);
    }

    public function postParseRow(&$r)
    {
        $mcrypt = $this->container->get('app_admin.helper.mcrypt');

        $r['accounts'] = Utils::countAccountsOnServer($this->container, $r['id']);
        $r['ip'] = $mcrypt->decrypt($r['encryptedIp']);
        $r['type'] = Utils::getServerType($this->container, $r['groupId']);
        $r['group'] = Utils::getServerGroup($this->container, $r['groupId']);

        unset($r['encryptedIp']);
        unset($r['encryptedUser']);
        unset($r['encryptedPass']);
    }
}