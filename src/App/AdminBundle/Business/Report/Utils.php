<?php
namespace App\AdminBundle\Business\Report;

use App\AdminBundle\Business;

class Utils
{
    public static function analyseGateway($controller, $fromDate, $toDate)
    {
        $em        = $controller->getDoctrine()->getEntityManager();
        $formatter = $controller->get('app_admin.helper.formatter');
        $config    = $controller->get('app_admin.helper.common')->getConfig();

        $query = $em->createQueryBuilder();

        $query->select('p.idGateway, SUM(p.amount) totalAmount')
            ->from('AppClientBundle:ClientPayment', 'p')
            ->groupBy('p.idGateway');
        if (!empty($fromDate)) {
            $fromDate = \DateTime::createFromFormat($config->getDateFormat(), $fromDate);
            $fromDate->setTime(0, 0, 0);
            $query->andWhere('p.payDate >= :fromDate')
                ->setParameter('fromDate', $fromDate);
        }
        if (!empty($toDate)) {
            $toDate = \DateTime::createFromFormat($config->getDateFormat(), $toDate);
            $toDate->setTime(23, 59, 59);
            $query->andWhere('p.payDate <= :toDate')
                ->setParameter('toDate', $toDate);
        }
        $result = $query->getQuery()->getResult();

        $data = array();
        foreach ($result as $r) {
            $gateway = $formatter->format($r['idGateway'], 'mapping', 'gateway_list');
            if (empty($gateway)) {
                $gateway = 'Other';
            }
            $data[] = array(
                'gateway' => $gateway,
                'amount'  => $r['totalAmount']
            );
        }

        return $data;
    }
    public static function accountantReport($container)
    {
        $excelService = $container->get('xls.service_xls5');

        // Sheet 0, clients
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('A1', "First Name");
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('B1', "Last Name");
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('C1', "Company");
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('D1', "Address Line 1");
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('E1', "Address Line 2");
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('F1', "City");
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('G1', "State");
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('H1', "Post Code");
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('I1', "Country");
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('J1', "Phone Number");
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('K1', "Email Address");
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('L1', "VAT Number");
        $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('M1', "Status");

        $clients = $container->getDoctrine()->getRepository('AppUserBundle:User')->findAll();
        for($i=0; $i<count($clients); $i++)
        {
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('A'.($i+2), $clients[$i]->getFirstName());
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('B'.($i+2), $clients[$i]->getLastName());
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('C'.($i+2), $clients[$i]->getCompany());
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('D'.($i+2), $clients[$i]->getAddress1());
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('E'.($i+2), $clients[$i]->getAddress2());
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('F'.($i+2), $clients[$i]->getCity());
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('G'.($i+2), $clients[$i]->getState());
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('H'.($i+2), $clients[$i]->getPostcode());
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('I'.($i+2), Business\Client\Utils::getCountryFromId($clients[$i]->getIdCountry()));
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('J'.($i+2), $clients[$i]->getPhone());
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('K'.($i+2), $clients[$i]->getEmail());
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('L'.($i+2), $clients[$i]->getVatNumber());
            $excelService->excelObj->setActiveSheetIndex(0)->setCellValue('M'.($i+2), Business\Client\Utils::getStatusFromId($clients[$i]->getStatus()));
        }
        $excelService->excelObj->getActiveSheet()->setTitle('Clients');
        return $excelService->getResponse();
    }
    /* Not actually needed but a nice function */
    private static function getXlsColByNumber($number)
    {
        $alphabet = range('A', 'Z');
        $row = "";
        while($divisor = $number % 26){
            $row = $alphabet[$divisor - 1] . $row;
            $number /= 26;
        }
        return $row;
    }
}