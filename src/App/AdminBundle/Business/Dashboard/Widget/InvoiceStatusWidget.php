<?php
namespace App\AdminBundle\Business\Dashboard\Widget;

class InvoiceStatusWidget extends AbstractWidget {
    const OPTION_THIS_MONTH = 1;
    const OPTION_LAST_MONTH = 2;

    public function build($idWidget, $widgetState) {
        $data = array();
        parent::commonBuild($data, $idWidget, $widgetState);

        return $this->render('AppAdminBundle:Dashboard:widget_invoice_status.html.twig', $data);
    }

    public function getViewOptions() {
        return array(
            self::OPTION_THIS_MONTH   => 'This month',
            self::OPTION_LAST_MONTH   => 'Last month'
        );
    }
    public function getDefaultOption() {
        return self::OPTION_THIS_MONTH;
    }

    protected function getInvoiceStatus($idOption) {     
        switch ($idOption) {
            case self::OPTION_THIS_MONTH:                
                $date = new \DateTime();                
                break;
            
            case self::OPTION_LAST_MONTH:
                $date = new \DateTime();
                $date->sub(new \DateInterval('P1M'));
                break;
        }

        list($year, $month, $numDay) = explode('-', $date->format('Y-m-t'));
        $startDate = new \DateTime();
        $startDate->setDate($year, $month, 1);
        $startDate->setTime(0, 0, 0);
        $endDate = new \DateTime();
        $endDate->setDate($year, $month, $numDay);
        $endDate->setTime(0, 0, 0);

        $helperMapping = $this->container->get('app_admin.helper.mapping');
        $statusMapping = $helperMapping->getMapping('invoice_status');
        $statusData = array();
        foreach ($statusMapping as $k => $v) {
            $statusData[$k] = 0;
        }

        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder();
        $query
            ->select('p.status, COUNT(p) as number')
            ->from('AppClientBundle:ClientInvoice', 'p')
            ->andWhere('p.issueDate >= :startDate')
            ->andWhere('p.issueDate <= :endDate')
            ->groupBy('p.status')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);
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

    public function loadWidget($idWidget, $idOption) {
        return json_encode(
            array(
                'data' => $this->getInvoiceStatus($idOption)           
            )
        );
    }
}