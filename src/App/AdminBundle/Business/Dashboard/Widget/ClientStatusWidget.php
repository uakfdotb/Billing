<?php
namespace App\AdminBundle\Business\Dashboard\Widget;

class ClientStatusWidget extends AbstractWidget {
    const ALL_TIME_YEAR = 9999;
    public function build($idWidget, $widgetState) {
        $data = array();
        parent::commonBuild($data, $idWidget, $widgetState);

        return $this->render('AppAdminBundle:Dashboard:widget_client_status.html.twig', $data);
    }
    
    public function getViewOptions() {
        return $this->getLastNYear() + array(self::ALL_TIME_YEAR => 'All time');
    }
    public function getDefaultOption() {
        return $this->getCurrentYear();
    }

    protected function getClientStatus($year) {     
        $helperMapping = $this->container->get('app_admin.helper.mapping');
        $statusMapping = $helperMapping->getMapping('client_status');
        $statusData = array();
        foreach ($statusMapping as $k => $v) {
            $statusData[$k] = 0;
        }

        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder();
        $query
            ->select('p.status, COUNT(p) as number')
            ->from('AppUserBundle:User', 'p')
            ->groupBy('p.status');
        if (self::ALL_TIME_YEAR != $year) {
            $query
                ->andWhere('YEAR(p.addedDate) = :year')
                ->setParameter('year', $year);
        }
        $result = $query->getQuery()->getResult();
        foreach ($result as $r) {
            $status = $r['status'];
            $number = intval($r['number']);
            $statusData[$status] = $number;
        }

        $data = array();
        foreach ($statusData as $k => $v) {
            if (!isset($statusMapping[$k])) {
                $data[] = array('(undefined)', $v);
            }
            else {
                $data[] = array($statusMapping[$k], $v);
            }
        }

        return $data;
    }
    public function loadWidget($idWidget, $year) {
        return json_encode(
            array(
                'data' => $this->getClientStatus($year)           
            )
        );
    }
}