<?php

namespace App\AdminBundle\Business\Invoice;

use App\ClientBundle\Entity;
use App\AdminBundle\Business;
use Proxies\__CG__\App\ClientBundle\Entity\ClientInvoice;
use Symfony\Component\HttpKernel\Kernel;
use Knp\Snappy\Pdf;
use App\ClientBundle\Entity\ClientEmail;

class Utils
{
    public static function sendInvoiceEmail($controller, $invoice, $msg)
    {
        // Get invoice information
        $doctrine     = $controller->get('doctrine');
        $formatter    = $controller->get('app_admin.helper.formatter');
        $client       = Business\GlobalUtils::getClientById($controller, $invoice->getIdClient());
        $config       = $controller->get('app_admin.helper.common')->getConfig();
        $businessName = $config->getBusinessName();
        $senderEmail  = $controller->get('service_container')->getParameter('email_address');
        $senderName   = $controller->get('service_container')->getParameter('email_sender_name');
        $billrHost    = $controller->get('service_container')->getParameter('main_host');
        $subdomain    = $controller->get('service_container')->getParameter('client_subdomain');
        $contacts     = Business\ClientContact\Utils::getContactEmailsByType($controller, $invoice->getIdClient(), 'billing');

        // Put data
        $data = array(
            'client'       => $client,
            'invoice'      => $invoice,
            'paymentUrl'   => sprintf('https://%s.%s/invoice/show/%s/%s', $subdomain, $billrHost, $invoice->getId(), $invoice->getInvoiceAccessToken()),
            'receiptUrl'   => '#',
            'total'        => $formatter->format($invoice->getTotalAmount(), 'money'),
            'issueDate'    => $formatter->format($invoice->getIssueDate(), 'date'),
            'dueDate'      => $formatter->format($invoice->getDueDate(), 'date'),
            'businessName' => $businessName
        );

        $subject = '';
        switch ($msg) {
            case 'invoice':
                $subject = "New invoice from $businessName";
                $content = self::generateEmailBody('invoice', $data, $config);
                break;
            case 'reminder':
                $subject = "Invoice reminder from $businessName";
                $content = self::generateEmailBody('reminder', $data, $config);
                break;
            case 'overdue':
                $subject = "[Urgent] Invoice overdue notice from $businessName";
                $content = self::generateEmailBody('overdue', $data, $config);
                break;
        }

        $pdfData = self::generatePdf($controller, $invoice);
        $attachmentName = sprintf('invoice-%s.pdf', $invoice->getNumber());

        try {
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($senderEmail, $senderName)
                ->setTo($client->getEmail())
                ->setBody($content, 'text/html')
                ->attach(\Swift_Attachment::newInstance($pdfData, $attachmentName, 'application/pdf'))
                ->setReplyTo(array($config->getDefaultEmail() => $config->getBusinessName()));

            foreach($contacts as $contact) $message->addCC($contact);

            $controller->get('mailer')->send($message);

            // Log email
            $clientEmail = new ClientEmail();
            $clientEmail->setIdClient($client->getId());
            $clientEmail->setTimestamp(new \DateTime());
            $clientEmail->setSubject($subject);
            $doctrine->getManager()->persist($clientEmail);
            $doctrine->getManager()->flush();
        } catch (\Exception $e) {
            return $e->getMessage();
        }
        return true;
    }

    public static function generatePdf($controller, $invoice)
    {
        $data = array();

        $routes = array('gateway' => 'app_admin_invoice_gateway', 'pdf' => 'app_admin_invoice_view');
        self::prepareInvoicePage($controller, $data, $invoice, 'pdf', $routes);

        $html   = $controller->get('templating')->render('AppAdminBundle:Invoice:view_pdf.html.twig', $data);
        
        $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
        $snappy->setOption('quiet', true);
        return $snappy->getOutputFromHtml($html);
    }

    public static function refund($controller, $idInvoice)
    {
        $em      = $controller->get('doctrine')->getEntityManager();
        $invoice = $controller->get('doctrine')->getRepository('AppClientBundle:ClientInvoice')->findOneById($idInvoice);
        if (!$invoice) {
            return false;
        }

        $invoice->setStatus(Business\Estimate\Constants::STATUS_REFUND);
        $em->persist($invoice);
        $em->flush();

        $clientPayment = new Entity\ClientPayment();
        $clientPayment->setIdGateway(0);
        $clientPayment->setTransaction('');
        $clientPayment->setPayDate(new \DateTime());
        $clientPayment->setAmount($invoice->getTotalPayment() * -1);
        $clientPayment->setIdInvoice($invoice->getId());
        $em->persist($clientPayment);
        $em->flush();
        return true;
    }

    public static function updateInvoiceStatus($container, $invoiceId)
    {
        $doctrine = $container->get('doctrine');
        $em       = $doctrine->getEntityManager();

        // Load invoice
        $invoice = $doctrine->getRepository('AppClientBundle:ClientInvoice')->findOneById($invoiceId);
        $client = $doctrine->getRepository('AppUserBundle:User')->findOneById($invoice->getIdClient());
        $taxValue = Business\Tax\Utils::calculateTaxByClient($container, $client, $invoice->getTax());

        // Invoice amount
        $query = $em->createQueryBuilder();
        $query->select("SUM(p.quantity * p.unitPrice) as totalAmount")
            ->from('AppClientBundle:ClientInvoiceItem', 'p')
            ->andWhere('p.idInvoice = :idInvoice')
            ->setParameter('idInvoice', $invoiceId);

        $totalAmount = $query->getQuery()->getSingleScalarResult();
        $discount    = round($totalAmount * $invoice->getDiscount(), 2);
        $tax         = round(($totalAmount - $discount) * $taxValue, 2);
        $sumAmount   = round($totalAmount - $discount + $tax, 2);

        // Payment
        $sumPayment = 0;
        $query      = $em->createQueryBuilder();
        $query->select("SUM(p.amount) as total")
            ->from('AppClientBundle:ClientPayment', 'p')
            ->andWhere('p.idInvoice = :idInvoice')
            ->setParameter('idInvoice', $invoice->getId());
        $sumPayment = $query->getQuery()->getSingleScalarResult();

        // Update status
        if ($sumPayment >= $sumAmount) {
            self::removeProforma($invoice, $container);
            $invoice->setStatus(ClientInvoice::STATUS_PAID);

            // Handle automation
            $matchingOrder = $doctrine->getRepository('AppClientBundle:ProductOrder')->findOneByIdInvoice($invoiceId);
            if(!empty($matchingOrder))
            {
                $container->get('app_admin.helper.automation_helper')->handlePostPaid($matchingOrder);
            }

        } else {
            if($invoice->getStatus() != ClientInvoice::STATUS_PROFORMA && $invoice->getStatus() != ClientInvoice::STATUS_WRITTEN_OFF) {
                $invoice->setStatus(ClientInvoice::STATUS_UNPAID);
                if ($invoice->getDueDate()) {
                    $today = new \DateTime("today midnight");
                    if ($invoice->getDueDate()->getTimestamp() < $today->getTimestamp()) {
                        $invoice->setStatus(ClientInvoice::STATUS_OVERDUE);
                    }
                }
            }
        }
        $invoice->setTotalAmount($sumAmount);
        $invoice->setTotalPayment($sumPayment);

        $em->persist($invoice);
        $em->flush();
    }


    public static function prepareInvoicePage($controller, &$data, $invoice, $mode = '', $routes = array())
    {
        $helperMapping         = $controller->get('app_admin.helper.mapping');
        $config                = $controller->get('app_admin.helper.common')->getConfig();
        $helperFormatter       = $controller->get('app_admin.helper.formatter');
        $data['config']        = $config;
        $data['gatewayConfig'] = $controller->get('app_admin.helper.common')->getGatewayConfig();

        $client = Business\GlobalUtils::getClientById($controller, $invoice->getIdClient());
        $data['invoice']            = $invoice;
        $data['invoice_issue_date'] = $helperFormatter->format($invoice->getIssueDate(), 'date');
        $data['invoice_due_date']   = $helperFormatter->format($invoice->getDueDate(), 'date');
        $data['client']             = $client;

        // Tax
        $taxValue = Business\Tax\Utils::calculateTaxByClient($controller, $client, $invoice->getTax());

        // Payment
        $sumPayment = $invoice->getTotalPayment();

        // Invoice Items
        $result       = $controller->get('doctrine')->getRepository('AppClientBundle:ClientInvoiceItem')->findByIdInvoice($invoice->getId());
        $invoiceItems = array();

        $sumSubTotal = 0;
        $sumDiscount = 0;
        $sumTax      = 0;
        $sumAmount   = 0;
        foreach ($result as $row) {
            $subTotal = $row->getQuantity() * $row->getUnitPrice();
            $discount = round($subTotal * $invoice->getDiscount(), 2);
            $tax      = round(($subTotal - $discount) * $taxValue, 2);
            $amount   = $subTotal - $discount;

            $sumSubTotal += $subTotal;
            $sumDiscount += $discount;
            $sumTax += $tax;
            $sumAmount += $amount;

            $invoiceItems[] = array(
                'type'        => $helperMapping->getMappingTitle('project_type', $row->getIdType()),
                'description' => $row->getDescription(),
                'quantity'    => $row->getQuantity(),
                'unitPrice'   => $helperFormatter->format($row->getUnitPrice(), 'money'),
                'discount'    => $helperFormatter->format($discount, 'money'),
                'tax'         => $helperFormatter->format($tax, 'money'),
                'amount'      => $helperFormatter->format($amount, 'money')
            );
        }

        $data['invoiceItems'] = $invoiceItems;
        $data['sumSubTotal']  = $helperFormatter->format($sumSubTotal, 'money');
        $data['sumDiscount']  = $helperFormatter->format($sumDiscount, 'money');
        $data['sumTax']       = $helperFormatter->format($sumTax, 'money');
        $data['sumAmount']    = $helperFormatter->format($sumAmount + $sumTax, 'money');
        $data['sumPayment']   = $helperFormatter->format($sumPayment, 'money');
        $data['sumAmountDue'] = $helperFormatter->format($sumAmount + $sumTax - $sumPayment, 'money');
        $data['taxPercent']   = ($sumAmount == 0) ? number_format(0, 2) : number_format(100*($sumTax / $sumAmount), 2);

        $data['status'] = $helperFormatter->format($invoice->getStatus(), 'mapping', 'invoice_status');

        $data['country'] = self::getCountryFromId($client->getIdCountry());

        $taxName = $controller->get('doctrine')->getRepository('AppClientBundle:Tax')->findOneById($invoice->getTax());
        $data['taxName'] = empty($taxName) ? "Tax" : $taxName->getName();

        // Links
        $data['getGatewaysUrl'] = $controller->get('router')->generate($routes['gateway'], array('id' => $invoice->getId()));
        $data['printPdfUrl']    = $controller->get('router')->generate($routes['pdf'], array('id' => $invoice->getId(), 'mode' => 'pdf'));

        $config->getLogo() ?
            $data['logo'] = 'data:image/jpg;base64,' . base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'].'/../'.$config->getLogo())) :
            $data['logo'] = NULL;


        if ($mode == 'pdf') {
            /** @var Kernel $kernel */
            $kernel = $controller->get('kernel');
            $image_path         = $kernel->locateResource('@AppClientBundle/Resources/public');
            $data['image_path'] = $image_path;
            $clientCssPath      = $kernel->locateResource('@AppClientBundle/Resources/public');
            $data['css']        = array(
                'pdf_reset.css' => file_get_contents($clientCssPath . '/pdf_reset.css'),
                'invoice.css'   => file_get_contents($clientCssPath . '/invoice.css'),
            );
        }
    }


    public static function updateInvoicePrefix($container, $invoiceId)
    {
        $config  = $container->get('app_admin.helper.common')->getConfig();
        $invoice = $container->get('doctrine')->getRepository('AppClientBundle:ClientInvoice')->findOneById($invoiceId);
        if ($invoice) {
            $invoice->setNumber(self::beautifyId($invoiceId, $config->getInvoicePrefix()));
            $em = $container->get('doctrine')->getEntityManager();
            $em->persist($invoice);
            $em->flush();
        }
    }

    /**
     * @param $id
     * @param string $prefix
     * @param int $paddingSize
     * @return string
     */
    public static function beautifyId($id, $prefix = '', $paddingSize = 6)
    {
        $digits = strlen($id);

        if ($digits >= $paddingSize) {
            return $prefix . $id;
        }

        return $prefix.str_repeat('0', $paddingSize-$digits).$id;
    }

    private static function removeProforma($invoice, $container)
    {
        $em = $container->get('doctrine')->getManager();
        $config = $container->get('app_admin.helper.common')->getConfig();
        if($config->getIsProformaInvoiceEnabled())
        {
            // If proforma invoices are enabled
            $newNumber = $config->getCountProformaPaidInvoice()+1;
            $invoice->setNumber(self::beautifyId($newNumber, $config->getInvoicePrefix()));
            $em->persist($invoice);

            $config->incrementCountProformaPaidInvoice();

            $em->flush();

        }
    }
    private static function generateEmailBody($type, $data, $config)
    {
        $replacements = [
            "{client_first_name}" => $data['client']->getFirstName(),
            "{client_last_name}"  => $data['client']->getLastName(),
            "{client_full_name}"  => $data['client']->getFirstName() . ' ' . $data['client']->getLastName(),
            "{invoice_id}"        => $data['invoice']->getNumber(),
            "{invoice_url}"       => $data['paymentUrl'],
            "{invoice_total}"     => $data['total'],
            "{invoice_issue_date}"=> $data['issueDate'],
            "{invoice_due_date}"  => $data['dueDate'],
            "{business_name}"     => $data['businessName']
        ];

        switch($type){
            case "invoice":
                $content = $config->getInvoiceEmail();
                break;
            case "reminder":
                $content = $config->getReminderEmail();
                break;
            case "overdue":
                $content = $config->getOverdueEmail();
                break;
            default:
                throw new \Exception("Email type not found");
                break;
        }
        foreach($replacements as $key => $value)
            $content = str_replace($key, $value, $content);

        return nl2br($content);
    }

    private static function getCountryFromId($id)
    {
        $array = [
            "" => "Select Country",
            "1" => "United States",
            "2" => "United Kingdom",
            "3" => "Afghanistan",
            "4" => "Albania",
            "5" => "Algeria",
            "6" => "American Samoa",
            "7" => "Andorra",
            "8" => "Angola",
            "9" => "Anguilla",
            "10" => "Antarctica",
            "11" => "Antigua and Barbuda",
            "12" => "Argentina",
            "13" => "Armenia",
            "14" => "Aruba",
            "15" => "Australia",
            "16" => "Austria",
            "17" => "Azerbaijan",
            "18" => "Bahamas",
            "19" => "Bahrain",
            "20" => "Bangladesh",
            "21" => "Barbados",
            "22" => "Belarus",
            "23" => "Belgium",
            "24" => "Belize",
            "25" => "Benin",
            "26" => "Bermuda",
            "27" => "Bhutan",
            "28" => "Bolivia",
            "29" => "Bosnia and Herzegovina",
            "30" => "Botswana",
            "31" => "Bouvet Island",
            "32" => "Brazil",
            "33" => "British Indian Ocean Territory",
            "34" => "Brunei Darussalam",
            "35" => "Bulgaria",
            "36" => "Burkina Faso",
            "37" => "Burundi",
            "38" => "Cambodia",
            "39" => "Cameroon",
            "40" => "Canada",
            "41" => "Cape Verde",
            "42" => "Cayman Islands",
            "43" => "Central African Republic",
            "44" => "Chad",
            "45" => "Chile",
            "46" => "China",
            "47" => "Christmas Island",
            "48" => "Cocos (Keeling) Islands",
            "49" => "Colombia",
            "50" => "Comoros",
            "51" => "Congo",
            "52" => "Congo, The Democratic Republic of The",
            "53" => "Cook Islands",
            "54" => "Costa Rica",
            "55" => "Cote D'ivoire",
            "56" => "Croatia",
            "57" => "Cuba",
            "58" => "Cyprus",
            "59" => "Czech Republic",
            "60" => "Denmark",
            "61" => "Djibouti",
            "62" => "Dominica",
            "63" => "Dominican Republic",
            "64" => "Ecuador",
            "65" => "Egypt",
            "66" => "El Salvador",
            "67" => "Equatorial Guinea",
            "68" => "Eritrea",
            "69" => "Estonia",
            "70" => "Ethiopia",
            "71" => "Falkland Islands (Malvinas)",
            "72" => "Faroe Islands",
            "73" => "Fiji",
            "74" => "Finland",
            "75" => "France",
            "76" => "French Guiana",
            "77" => "French Polynesia",
            "78" => "French Southern Territories",
            "79" => "Gabon",
            "80" => "Gambia",
            "81" => "Georgia",
            "82" => "Germany",
            "83" => "Ghana",
            "84" => "Gibraltar",
            "85" => "Greece",
            "86" => "Greenland",
            "87" => "Grenada",
            "88" => "Guadeloupe",
            "89" => "Guam",
            "90" => "Guatemala",
            "91" => "Guinea",
            "92" => "Guinea-bissau",
            "93" => "Guyana",
            "94" => "Haiti",
            "95" => "Heard Island and Mcdonald Islands",
            "96" => "Holy See (Vatican City State)",
            "97" => "Honduras",
            "98" => "Hong Kong",
            "99" => "Hungary",
            "100" => "Iceland",
            "101" => "India",
            "102" => "Indonesia",
            "103" => "Iran, Islamic Republic of",
            "104" => "Iraq",
            "105" => "Ireland",
            "106" => "Israel",
            "107" => "Italy",
            "108" => "Jamaica",
            "109" => "Japan",
            "110" => "Jordan",
            "111" => "Kazakhstan",
            "112" => "Kenya",
            "113" => "Kiribati",
            "114" => "Korea, Democratic People's Republic of",
            "115" => "Korea, Republic of",
            "116" => "Kuwait",
            "117" => "Kyrgyzstan",
            "118" => "Lao People's Democratic Republic",
            "119" => "Latvia",
            "120" => "Lebanon",
            "121" => "Lesotho",
            "122" => "Liberia",
            "123" => "Libyan Arab Jamahiriya",
            "124" => "Liechtenstein",
            "125" => "Lithuaniav",
            "126" => "Luxembourg",
            "127" => "Macao",
            "128" => "Macedonia, The Former Yugoslav Republic of",
            "129" => "Madagascar",
            "130" => "Malawi",
            "131" => "Malaysia",
            "132" => "Maldives",
            "133" => "Mali",
            "134" => "Malta",
            "135" => "Marshall Islands",
            "136" => "Martinique",
            "137" => "Mauritania",
            "138" => "Mauritius",
            "139" => "Mayotte",
            "140" => "Mexico",
            "141" => "Micronesia, Federated States of",
            "142" => "Moldova, Republic of",
            "143" => "Monaco",
            "144" => "Mongolia",
            "145" => "Montserrat",
            "146" => "Morocco",
            "147" => "Mozambique",
            "148" => "Myanmar",
            "149" => "Namibia",
            "150" => "Nauru",
            "151" => "Nepal",
            "152" => "Netherlands",
            "153" => "Netherlands Antilles",
            "154" => "New Caledonia",
            "155" => "New Zealand",
            "156" => "Nicaragua",
            "157" => "Niger",
            "158" => "Nigeria",
            "159" => "Niue",
            "160" => "Norfolk Island",
            "161" => "Northern Mariana Islands",
            "162" => "Norway",
            "163" => "Oman",
            "164" => "Pakistan",
            "165" => "Palau",
            "166" => "Palestinian Territory, Occupied",
            "167" => "Panama",
            "168" => "Papua New Guinea",
            "169" => "Paraguay",
            "170" => "Peru",
            "171" => "Philippines",
            "172" => "Pitcairn",
            "173" => "Poland",
            "174" => "Portugal",
            "175" => "Puerto Rico",
            "176" => "Qatar",
            "177" => "Reunion",
            "178" => "Romania",
            "179" => "Russian Federation",
            "180" => "Rwanda",
            "181" => "Saint Helena",
            "182" => "Saint Kitts and Nevis",
            "183" => "Saint Lucia",
            "184" => "Saint Pierre and Miquelon",
            "185" => "Saint Vincent and The Grenadines",
            "186" => "Samoa",
            "187" => "San Marino",
            "188" => "Sao Tome and Principe",
            "189" => "Saudi Arabia",
            "190" => "Senegal",
            "191" => "Serbia and Montenegro",
            "192" => "Seychelles",
            "193" => "Sierra Leone",
            "194" => "Singapore",
            "195" => "Slovakia",
            "196" => "Slovenia",
            "197" => "Solomon Islands",
            "198" => "Somalia",
            "199" => "South Africa",
            "200" => "South Georgia and The South Sandwich Islands",
            "201" => "Spain",
            "202" => "Sri Lanka",
            "203" => "Sudan",
            "204" => "Suriname",
            "205" => "Svalbard and Jan Mayen",
            "206" => "Swaziland",
            "207" => "Sweden",
            "208" => "Switzerland",
            "209" => "Syrian Arab Republic",
            "210" => "Taiwan, Province of China",
            "211" => "Tajikistan",
            "212" => "Tanzania, United Republic of",
            "213" => "Thailand",
            "214" => "Timor-leste",
            "215" => "Togo",
            "216" => "Tokelau",
            "217" => "Tonga",
            "218" => "Trinidad and Tobago",
            "219" => "Tunisia",
            "220" => "Turkey",
            "221" => "Turkmenistan",
            "222" => "Turks and Caicos Islands",
            "223" => "Tuvalu",
            "224" => "Uganda",
            "225" => "Ukraine",
            "226" => "United Arab Emirates",
            "227" => "United Kingdom",
            "228" => "United States",
            "229" => "United States Minor Outlying Islands",
            "230" => "Uruguay",
            "231" => "Uzbekistan",
            "232" => "Vanuatu",
            "233" => "Venezuela",
            "234" => "Viet Nam",
            "235" => "Virgin Islands, British",
            "236" => "Virgin Islands, U.S.",
            "237" => "Wallis and Futuna",
            "238" => "Western Sahara",
            "239" => "Yemen",
            "240" => "Zambia",
            "241" => "Zimbabwe",
        ];
        foreach($array as $key => $value)
        {
            if($key == $id) return $value;
        }
        return "";
    }
}

