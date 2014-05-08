<?php
namespace App\AdminBundle\Business\Client;

use App\ClientBundle\Entity;
use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\AdminBundle\Business;
use Knp\Snappy\Pdf;

class StatementGenerator extends BaseCreateHandler {

    public function getDefaultValues()
    {
        return;
    }

    public function buildForm($builder)
    {
        $builder->add('fromDate', 'date_picker', array(
            'attr'     => array(
                'placeholder' => 'FROM'
            ),
            'required' => true,
            'widget'   => 'single_text'
        ));

        $builder->add('toDate', 'date_picker', array(
            'attr'     => array(
                'placeholder' => 'TO'
            ),
            'required' => true,
            'widget'   => 'single_text'
        ));
        return $builder;
    }

    public static function prepareStatementPage($controller, &$data, $invoices, $payments, $mode = '')
    {
        $helperMapping         = $controller->get('app_admin.helper.mapping');
        $config                = $controller->get('app_admin.helper.common')->getConfig();
        $helperFormatter       = $controller->get('app_admin.helper.formatter');
        $data['config']        = $config;

        $data['statement_date'] = $helperFormatter->format(new \DateTime(), 'date');

        $data['client'] = Business\GlobalUtils::getClientById($controller->getContainer(), $invoices[0]->getIdClient());

        foreach($invoices as $invoice){
            $result = $controller->get('doctrine')->getRepository('AppClientBundle:ClientInvoiceItem')->findByIdInvoice($invoice->getId());
            $sumAmount = 0;
            foreach ($result as $row) {
                $subTotal = $row->getQuantity() * $row->getUnitPrice();
                $discount = round($subTotal * $invoice->getDiscount(), 2);
                $tax      = round(($subTotal - $discount) * $invoice->getTax(), 2);
                $amount   = $subTotal - $discount + $tax;

                $sumAmount += $amount;
            }
            $data['invoices'][] = array(
                'subject' => $invoice->getSubject(),
                'date'    => $invoice->getIssueDate(),
                'credit'  => 0,
                'debit'   => $sumAmount

            );
        }
        foreach($payments as $payment){
            $data["payments"][] = array(
                'subject' => "Payment received - thank you",
                'date'    => $payment->getPayDate(),
                'credit'  => $payment->getAmount(),
                'debit'   => 0
            );
        }

        // Merge & sort arrays
        $data['statement_items'] = array_merge($data["invoices"], $data['payments']);
        usort($data['statement_items'], function($a, $b){
            return ($a['date'] < $b['date']) ?  -1 : 1;
        });

        // Add balance & formatting to each item
        $balance = 0;
        for($i=0; $i < count($data['statement_items']); $i++){
            $balance += ($data['statement_items'][$i]['credit'] - $data['statement_items'][$i]['debit']);
            $data['statement_items'][$i]['balance'] = $helperFormatter->format($balance, 'money');
            $data['statement_items'][$i]['date']    = $helperFormatter->format($data['statement_items'][$i]['date'], 'date');
            $data['statement_items'][$i]['credit']  = $helperFormatter->format($data['statement_items'][$i]['credit'], 'money');
            $data['statement_items'][$i]['debit']   = $helperFormatter->format($data['statement_items'][$i]['debit'], 'money');
        }

        $data['country'] = Business\GlobalUtils::getCountryFromId($data['client']->getIdCountry());

        // Logo path from parameters
        $config->getLogo() ?
            $data['logo'] = 'data:image/jpg;base64,' . base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/../'.$config->getLogo())) :
            $data['logo'] = NULL;

        if ($mode == 'pdf') {
            /** @var Kernel $kernel */
            $kernel = $controller->getContainer()->get('kernel');
            $image_path         = $kernel->locateResource('@AppClientBundle/Resources/public');
            $data['image_path'] = $image_path;
            $clientCssPath      = $kernel->locateResource('@AppClientBundle/Resources/public');
            $data['css']        = array(
                'pdf_reset.css' => file_get_contents($clientCssPath . '/pdf_reset.css'),
                'invoice.css'   => file_get_contents($clientCssPath . '/invoice.css'),
            );
        }
    }

    public static function generatePdf($controller, $invoice)
    {
        $data = array();

        self::prepareInvoicePage($controller, $data, $invoices, $payments, 'pdf');

        $html   = $controller->get('templating')->render('AppAdminBundle:Statement:view_pdf.html.twig', $data);

        $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
        $snappy->setOption('quiet', true);
        return $snappy->getOutputFromHtml($html);
    }
}