<?php
namespace App\AdminBundle\Business\Dashboard\Widget;

class ProfitWidget extends AbstractWidget {
    const OPTION_YEAR   = 1;
    const OPTION_MONTH  = 2;
    const OPTION_WEEK   = 3;
    const OPTION_DAY    = 4;

    public function build($idWidget, $widgetState) {
        $data = array();
        parent::commonBuild($data, $idWidget, $widgetState);

        return $this->render('AppAdminBundle:Dashboard:widget_profit.html.twig', $data);
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
                $interval1Day = new \DateInterval('P1D');;
                while (intval($date->format('N')) != 1) {
                    $date->sub($interval1Day);
                }
                return $date;
            case self::OPTION_DAY:
                return $date;
        }
    }
    
    protected function getProfit($idOption, $startDate, $endDate) {
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

        $salesData = $this->cloneArray($analysisArray);
        $salesData = $this->parseAmount($salesData, $salesQuery);   
        $expensesData = $this->cloneArray($analysisArray);
        $expensesData = $this->parseAmount($expensesData, $expensesQuery);
        $profitData = $this->cloneArray($analysisArray);
        foreach ($profitData as $k => $temp) {
            $profitData[$k] = $salesData[$k] - $expensesData[$k];
        }
        
        return array(
            'data'         => $profitData,
            'categories'   => $categories
        );
    }

    public function getPreviousProfitData($idOption, $date) {        
        switch ($idOption) {
            case self::OPTION_YEAR:
                $interval = new \DateInterval('P1Y');                
                break;
            case self::OPTION_MONTH:
                $interval = new \DateInterval('P1M');
                break;
            case self::OPTION_WEEK:
                $interval = new \DateInterval('P7D');
                break;
            case self::OPTION_DAY:
                $interval = new \DateInterval('P1D');
                break;
        }

        $interval1s = new \DateInterval('PT1S');
        for ($i = 1; $i <= 2; $i++) {
            $startDate = clone $date;
            $startDate = $startDate->sub($interval);
            $endDate = clone $date;
            $endDate = $endDate->sub($interval1s);
            $profit = $this->getProfit($idOption, $startDate, $endDate);
            $profitAmount = $this->getSumOfArray($profit['data']);

            switch ($idOption) {
                case self::OPTION_YEAR:
                    $label = $startDate->format('Y');
                    break;
                case self::OPTION_MONTH:
                    $label = $startDate->format('F');
                    break;
                case self::OPTION_WEEK:
                    $label = 'Week '.$i;
                    break;
                case self::OPTION_DAY:
                    $label = $startDate->format('d/m/Y');
                    break;
            }
            $data[] = array(
                'label'  => $label,
                'amount' => $profitAmount
            );
            $date = clone $startDate;
        }
        return $data;
    }

    public function loadWidget($idWidget, $idOption) {        
        $startDate = $this->getStartDateOf($idOption);
        $endDate = new \DateTime();
        $data = $this->getProfit($idOption, $startDate, $endDate);
        $profitData = $data['data'];
        $categories = $data['categories'];
        $sum = $this->getSumOfArray($profitData);

        $previousData = $this->getPreviousProfitData($idOption, $startDate);
        foreach ($previousData as $k => $v) {
            $previousData[$k]['percentage'] = $this->computeChangePercentage($v['amount'], $sum);
        }

        return json_encode(
            array(
                'data'        => $this->removeArrayKey($profitData),
                'categories'  => $categories,
                'total'       => number_format($sum, 0),
                'previous'    => $previousData
            )
        );
    }
}