<?php
namespace App\AdminBundle\Business\Dashboard\Widget;

use App\AdminBundle\Business;

class TotalValueOfSalesWidget extends AbstractWidget {
    const ALL_TIME_YEAR = 9999;

    public function build($idWidget, $widgetState) {
        $data = array();
        parent::commonBuild($data, $idWidget, $widgetState);

        return $this->render('AppAdminBundle:Dashboard:widget_total_value_of_sales.html.twig', $data);
    }

    public function getViewOptions() {
        return $this->getLastNYear() + array(self::ALL_TIME_YEAR => 'All time');
    }
    public function getDefaultOption() {
        return $this->getCurrentYear();
    }
    
    protected function getTotalValueOfSales($year) {
        $query = $this->getSalesQuery('month');
        if ($year != self::ALL_TIME_YEAR) {
            $query->andWhere('YEAR(p.issueDate) = :year')
                ->setParameter('year', $year);        
        }

        $data = $this->getEmptyMonthArray();
        return $this->parseAmount($data, $query);
    }

    public function loadWidget($idWidget, $year) {
        $data = $this->getTotalValueOfSales($year);
        if ($year == self::ALL_TIME_YEAR) {
            return json_encode(
                array(
                    'data'        => $this->removeArrayKey($data),
                    'total'       => number_format($this->getSumOfArray($data), 0),
                    'percentage'  => 'NA'
                )
            );
        }
        else {
            $sum = $this->getSumOfArray($data);
            $previousData = $this->getTotalValueOfSales($year-1);
            $previousSum = $this->getSumOfArray($previousData);
            $changePercentage = $this->computeChangePercentage($previousSum, $sum);

            return json_encode(
                array(
                    'data'        => $this->removeArrayKey($data),
                    'total'       => number_format($sum, 0),
                    'percentage'  => $changePercentage
                )
            );
        }
    }
}