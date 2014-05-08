<?php
namespace App\AdminBundle\Business\Dashboard\Widget;

class BankBalanceWidget extends AbstractWidget {
    const OPTION_YEAR   = 1;
    const OPTION_MONTH  = 2;
    const OPTION_WEEK   = 3;
    const OPTION_DAY    = 4;

    public function build($idWidget, $widgetState) {
        $data = array();
        parent::commonBuild($data, $idWidget, $widgetState);

        return $this->render('AppAdminBundle:Dashboard:widget_bank_balance.html.twig', $data);
    }

    public function getViewOptions() {
        return array(
            self::OPTION_YEAR   => 'Year',
            self::OPTION_MONTH  => 'Month',
            self::OPTION_WEEK   => 'Week',
            self::OPTION_DAY    => 'Day'
        );
    }
    public function getDefaultOption() {
        return self::OPTION_YEAR;
    }

    protected function getStartDateOf($idOption) {
        $date = new \DateTime();
        $date->setTime(0, 0, 0);
        list($year, $month, $day) = explode('-', $date->format('Y-m-d'));

        switch ($idOption) {
            case self::OPTION_YEAR:
                $date->setDate($year, 1, 1);
                return $date;
            case self::OPTION_MONTH:
                $date->setDate($year, $month, 1);
                return $date;
            case self::OPTION_WEEK:
                $interval1Day = new \DateInterval('P1D');
                while (intval($date->format('N')) != 1) {
                    $date->sub($interval1Day);
                }
                return $date;
            case self::OPTION_DAY:
                return $date;
        }
    }

    protected function getCurrentBalance($date) {
        $em = $this->getEntityManager();
        $salesQuery = $em->createQueryBuilder();
        $salesQuery->select('SUM(p.totalAmount) as totalAmount')
            ->from('AppClientBundle:ClientInvoice', 'p')
            ->andWhere('p.issueDate < :date')
            ->setParameter('date', $date);
        $salesAmount = floatval($salesQuery->getQuery()->getSingleScalarResult());

        $expensesQuery = $em->createQueryBuilder();
        $expensesQuery->select('SUM(ac.amount) as totalAmount')
            ->from('AppClientBundle:SupplierPurchase', 'p')
            ->leftJoin('AppClientBundle:AccountTransaction', 'ac', 'WITH', 'ac.id = p.idAccountTransaction')
            ->andWhere('p.purchaseDate < :date')
            ->setParameter('date', $date);
        $expensesAmount = floatval($expensesQuery->getQuery()->getSingleScalarResult());

        return $salesAmount - $expensesAmount;
    }

    protected function getBankBalance($idOption) {
        $startDate = $this->getStartDateOf($idOption);
        $endDate = new \DateTime();

        $currentBalance = 0;
        switch ($idOption) {
            case self::OPTION_YEAR:
                $salesQuery = $this->getSalesQuery('month', $startDate, $endDate);          
                $expensesQuery = $this->getExpensesQuery('month', $startDate, $endDate);
                $analysisArray = $this->getEmptyMonthArray();                
                $categories = $this->getMonthLabels();
                break;
            case self::OPTION_MONTH:
                $salesQuery = $this->getSalesQuery('day', $startDate, $endDate);          
                $expensesQuery = $this->getExpensesQuery('day', $startDate, $endDate);
                $analysisArray = $this->getEmptyDayArray($startDate);                
                $categories = $this->getDayLabels($startDate);                
                break;
            case self::OPTION_WEEK:
                $salesQuery = $this->getSalesQuery('day', $startDate, $endDate);            
                $expensesQuery = $this->getExpensesQuery('day', $startDate, $endDate);
                $analysisArray = $this->getEmptyWeekDayArray($startDate);                
                $categories = $this->getWeekDayLabels();
                break;
            case self::OPTION_DAY:
                $salesQuery = $this->getSalesQuery('hour', $startDate, $endDate);              
                $expensesQuery = $this->getExpensesQuery('hour', $startDate, $endDate);
                $analysisArray = $this->getEmptyHourArray();                
                $categories = $this->getHourLabels();
                break;
        }     

        $currentBalance = $this->getCurrentBalance($startDate);
        $salesData = $this->cloneArray($analysisArray);
        $salesData = $this->parseAmount($salesData, $salesQuery);   
        $expensesData = $this->cloneArray($analysisArray);
        $expensesData = $this->parseAmount($expensesData, $expensesQuery);   
        $balanceData = $this->cloneArray($analysisArray);         
        foreach ($balanceData as $k => $temp) {
            $currentBalance += $salesData[$k] - $expensesData[$k];
            $balanceData[$k] = $currentBalance;
        }
        
        return array(
            'data'            => $balanceData,
            'currentBalance'  => $currentBalance,
            'categories'      => $categories
        );
    }

    public function loadWidget($idWidget, $idOption) {
        $data = $this->getBankBalance($idOption);
        $bankBalanceData = $data['data'];
        $categories = $data['categories'];

        return json_encode(
            array(
                'data'        => $this->removeArrayKey($bankBalanceData),
                'categories'  => $categories,
                'total'       => number_format($data['currentBalance'], 0),
                'percentage'  => 'NA' // Fix me
            )
        );
    }
}