<?php
namespace App\AdminBundle\Business\Dashboard\Widget;

use App\AdminBundle\Business;

abstract class AbstractWidget {
    public $container;
    public function __construct($container) {
        $this->container = $container;
    }

    abstract function build($idWidget, $widgetState);
    abstract function loadWidget($idWidget, $widgetState);
    abstract function getViewOptions();
    abstract function getDefaultOption();

    public function render($template, $data) {
        return $this->container->get('templating')->render($template, $data);
    }

    public function commonBuild(&$data, $idWidget, $widgetState) {        
        $data['widgetElementId'] = 'widget_'.$idWidget;        
        $data['idWidget'] = $idWidget;
        $data['widgetUpdateUrl'] = $this->container->get('router')->generate('app_admin_dashboard_load_widget');

        $formatter               = $this->container->get('app_admin.helper.formatter');
        $data['currencyCode']    = $formatter->getCurrencyCode();
        
        // Build viewby
        $selectedOption = $widgetState;
        $viewOptions = $this->getViewOptions();
        if (empty($widgetState) || !isset($viewOptions[$widgetState])) {
            $selectedOption = $this->getDefaultOption();
        }
        $data['viewby'] = array(
            'options'   => $viewOptions,
            'selected'  => $selectedOption
        );
    }

    public function getCurrentYear() {
        $currentDateTime = new \DateTime();
        return intval($currentDateTime->format('Y'));
    }
    public function getCurrentMonth() {
        $currentDateTime = new \DateTime();
        return intval($currentDateTime->format('m'));
    }
    public function getCurrentDate() {
        $currentDateTime = new \DateTime();
        return intval($currentDateTime->format('d'));
    }


    public function getLastNYear($n = 5) {
        $currentYear = $this->getCurrentYear();
        $yearArr = array();
        for ($y = $currentYear; $y > $currentYear - $n; $y--) {
            $yearArr[$y] = $y;
        }
        return $yearArr;
    }

    public function getEmptyMonthArray() {
        $arr = array();
        for ($i = 1; $i <= 12; $i++) {
            $arr[$i] = 0;
        }
        return $arr;
    }
    public function getMonthLabels() {
        return array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
    }

    public function getEmptyDayArray($dateOfMonth) {
        $numDay = $dateOfMonth->format('t');
        $arr = array();
        for ($i = 1; $i <= $numDay; $i++) {
            $arr[$i] = 0;
        }
        return $arr;
    }
    public function getDayLabels($dateOfMonth) {
        $numDay = $dateOfMonth->format('t');
        $arr = array();
        for ($i = 1; $i <= $numDay; $i++) {
            $arr[] = $i;
        }
        return $arr;
    }

    public function getEmptyWeekDayArray($date) {
        $cDate = clone $date;
        $arr = array();
        $interval1Day = new \DateInterval('P1D');
        for ($i = 0; $i < 7; $i++) {
            $d = intval($cDate->format('d'));
            $cDate->add($interval1Day);
            $arr[$d] = 0;
        }
        return $arr;
    }
    public function getWeekDayLabels() {
        return array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
    }

    public function getEmptyHourArray() {
        $arr = array();
        for ($i = 0; $i < 24; $i++) {
            $arr[$i] = 0;
        }
        return $arr;
    }
    public function getHourLabels() {
        $arr = array();
        for ($i = 0; $i < 24; $i++) {
            $arr[] = $i;
        }
        return $arr;
    }
    
    public function getSumOfArray($arr) {
        $sum = 0;
        foreach ($arr as $v) {
            $sum += $v;
        }
        return $sum;
    }

    public function cloneArray($arr) {
        $newArr = array();
        foreach ($arr as $k => $v) {
            $newArr[$k] = $v;
        }
        return $newArr;
    }

    public function computeChangePercentage($previous, $current) {
        if ($previous == 0) {
            return 'NA';
        }

        $changePercentage = ($current * 100 / $previous) - 100;
        return number_format($changePercentage, 2);
    }

    public function removeArrayKey($arr) {
        $filteredArr = array();
        foreach ($arr as $v) {
            $filteredArr[] = $v;
        }
        return $filteredArr;
    }

    public function getEntityManager() {
        $em = $this->container->get('doctrine')->getManager();
        $emConfig = $em->getConfiguration();
        $emConfig->addCustomDatetimeFunction('MONTH',   'App\AdminBundle\Extension\Doctrine\MonthExtension');
        $emConfig->addCustomDatetimeFunction('YEAR',    'App\AdminBundle\Extension\Doctrine\YearExtension');
        $emConfig->addCustomDatetimeFunction('DAY',     'App\AdminBundle\Extension\Doctrine\DayExtension');
        $emConfig->addCustomDatetimeFunction('HOUR',    'App\AdminBundle\Extension\Doctrine\HourExtension');

        return $em;
    }

    public function getSalesQuery($groupBy = '', $startDate = '', $endDate = '') {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder();
        $query
            ->from('AppClientBundle:ClientInvoice', 'p')
            //->andWhere('p.status = :paidStatus')            
            //->setParameter('paidStatus', Business\Invoice\Constants::STATUS_PAID)
            ->groupBy('key');
        switch ($groupBy) {
            case 'month':
                $query->select('MONTH(p.issueDate) key, SUM(p.totalAmount) amount, COUNT(p) number');
                break;            
            case 'day':
                $query->select('DAY(p.issueDate) key, SUM(p.totalAmount) amount, COUNT(p) number');
                break;
            case 'hour':
                $query->select('HOUR(p.issueDate) key, SUM(p.totalAmount) amount, COUNT(p) number');
        }        
        if (!empty($startDate)) {
            $query->andWhere('p.issueDate >= :startDate')
                ->setParameter('startDate', $startDate);
        }
        if (!empty($endDate)) {
            $query->andWhere('p.issueDate <= :endDate')
                ->setParameter('endDate', $endDate);
        }

        return $query;
    }

    public function getExpensesQuery($groupBy = '', $startDate = '', $endDate = '') {
        $em = $this->getEntityManager();
        $query = $em->createQueryBuilder();
        $query
            ->from('AppClientBundle:SupplierPurchase', 'p')
            ->leftJoin('AppClientBundle:AccountTransaction', 'ac', 'WITH', 'ac.id = p.idAccountTransaction')
            ->groupBy('key');
        switch ($groupBy) {
            case 'month':
                $query->select('MONTH(p.purchaseDate) key, SUM(ac.amount) amount, COUNT(p) number');
                break;            
            case 'day':
                $query->select('DAY(p.purchaseDate) key, SUM(ac.amount) amount, COUNT(p) number');
                break;
            case 'hour':
                $query->select('HOUR(p.purchaseDate) key, SUM(ac.amount) amount, COUNT(p) number');
        }
        if (!empty($startDate)) {
            $query->andWhere('p.purchaseDate >= :startDate')
                ->setParameter('startDate', $startDate);
        }
        if (!empty($endDate)) {
            $query->andWhere('p.purchaseDate <= :endDate')
                ->setParameter('endDate', $endDate);
        }

        return $query;
    }

    public function parseAmount($data, $query) {
        $result = $query->getQuery()->getResult();
        foreach ($result as $r) {
            $key = trim($r['key']);
            $amount = floatval($r['amount']);

            if (isset($data[$key])) {
                $data[$key] = $amount;
            }
        }
        return $data;
    }

    public function parseNumber($data, $query) {
        $result = $query->getQuery()->getResult();
        foreach ($result as $r) {
            $key = trim($r['key']);
            $number = intval($r['number']);

            if (isset($data[$key])) {
                $data[$key] = $number;
            }
        }
        return $data;
    }
}