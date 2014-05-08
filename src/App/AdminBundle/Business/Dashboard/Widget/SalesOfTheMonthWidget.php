<?php
namespace App\AdminBundle\Business\Dashboard\Widget;

class SalesOfTheMonthWidget extends AbstractWidget {
    const OPTION_LAST_MONTH                  = 1;
    const OPTION_MONTH_BEFORE_LAST           = 2;
    const OPTION_6_MONTHS_AGO                = 3;
    const OPTION_SAME_MONTH_LAST_YEAR        = 4;
    const OPTION_SAME_MONTH_2_YEARS_AGO      = 5;
    const OPTION_SAME_MONTH_3_YEARS_AGO      = 6;
    const OPTION_SAME_MONTH_5_YEARS_AGO      = 7;

    public function build($idWidget, $widgetState) {
        $data = array();
        parent::commonBuild($data, $idWidget, $widgetState);

        return $this->render('AppAdminBundle:Dashboard:widget_sales_of_the_month.html.twig', $data);
    }

    public function getViewOptions() {
        return array(
            self::OPTION_LAST_MONTH                => 'Last month',
            self::OPTION_MONTH_BEFORE_LAST         => 'Month before last',
            self::OPTION_6_MONTHS_AGO              => '6 months ago',
            self::OPTION_SAME_MONTH_LAST_YEAR      => 'Same month last year',
            self::OPTION_SAME_MONTH_2_YEARS_AGO    => 'Same month 2 years ago',
            self::OPTION_SAME_MONTH_3_YEARS_AGO    => 'Same month 3 years ago',
            self::OPTION_SAME_MONTH_5_YEARS_AGO    => 'Same month 5 years ago',
        );
    }
    public function getDefaultOption() {
        return self::OPTION_LAST_MONTH;
    }

    public function getLastDate($date, $idOption) {
        switch ($idOption) {
            case self::OPTION_LAST_MONTH:
                $interval = new \DateInterval('P1M');
                break;
            case self::OPTION_MONTH_BEFORE_LAST:
                $interval = new \DateInterval('P2M');
                break;
            case self::OPTION_6_MONTHS_AGO:
                $interval = new \DateInterval('P6M');
                break;
            case self::OPTION_SAME_MONTH_LAST_YEAR:
                $interval = new \DateInterval('P1Y');
                break;
            case self::OPTION_SAME_MONTH_2_YEARS_AGO:
                $interval = new \DateInterval('P2Y');
                break;
            case self::OPTION_SAME_MONTH_3_YEARS_AGO:
                $interval = new \DateInterval('P3Y');
                break;
            case self::OPTION_SAME_MONTH_5_YEARS_AGO:
                $interval = new \DateInterval('P5Y');
                break;
        }
        $date->sub($interval);
        return $date;
    }

    protected function getSalesOfTheMonth($date) {
        list($year, $month, $numDay) = explode('-', $date->format('Y-m-t'));
        $startDate = clone $date;
        $startDate->setDate($year, $month, 1);
        $startDate->setTime(0, 0, 0);
        $endDate = clone $date;
        $endDate->setDate($year, $month, $numDay);
        $endDate->setTime(23, 59, 59);

        $query = $this->getSalesQuery('day', $startDate, $endDate);
        $data = $this->getEmptyDayArray($date);
        return $this->parseNumber($data, $query);
    }

    public function loadWidget($idWidget, $idOption) {
        $date = new \DateTime();
        $currentData = $this->getSalesOfTheMonth($date);
        $currentSum = $this->getSumOfArray($currentData);

        $date = $this->getLastDate($date, $idOption);
        $lastData = $this->getSalesOfTheMonth($date);
        $lastSum = $this->getSumOfArray($lastData);

        $changePercentage = $this->computeChangePercentage($lastSum, $currentSum);

        return json_encode(
            array(
                'current'     => $this->removeArrayKey($currentData),
                'last'        => $this->removeArrayKey($lastData),
                'total'       => number_format($currentSum, 0),
                'percentage'  => $changePercentage
            )
        );
    }
}