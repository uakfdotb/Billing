<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Knp\Snappy\Pdf;
use App\AdminBundle\Business;

class ClientController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'order', 'edit', 'import', 'import_proceed', 'view'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('customer/clients');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Client',
            'base_route'     => 'app_admin_client',
            'base_view'      => 'AppAdminBundle:Client',
            'grid_title'     => 'Client List',
            'grid_handler'   => 'app_admin.business.client.grid_handler',
            'create_handler' => 'app_admin.business.client.create_handler',
            'edit_handler'   => 'app_admin.business.client.edit_handler',
            'delete_handler' => 'app_admin.business.client.delete_handler'
        );
    }

    public function postList(&$data, $action)
    {
        $data['importUrl'] = $this->get('router')->generate('app_admin_client_import');
    }

    public function viewAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'view');

        $clientId = $this->get('request')->query->get('id');
        $handler = $this->get('app_admin.business.client.statement_generator');
        $handler->execute();
        $data['statement_form'] = $this->get('app_admin.helper.form')->buildEditFormView($handler, array('route' => 'app_admin_client_statement'));

        $data['summary'] = array(
            'clientId'        => $clientId,
            'details'         => $this->getDoctrine()->getRepository('AppUserBundle:User')->findOneById($clientId),
            'balance'         => $this->get('app_admin.helper.formatter')->format(Business\Client\Utils::computeBalance($this->getContainer(), $clientId), 'money'),
            'numProjects'     => Business\Client\Utils::getNumberOf($this->getContainer(), 'ClientProject', $clientId),
            'numEstimates'    => Business\Client\Utils::getNumberOf($this->getContainer(), 'ClientEstimate', $clientId),
            'numInvoices'     => Business\Client\Utils::getNumberOf($this->getContainer(), 'ClientInvoice', $clientId),
            'numCredits'      => Business\Client\Utils::getNumberOf($this->getContainer(), 'ClientCreditNote', $clientId),
            'numRecurring'    => Business\Client\Utils::getNumberOf($this->getContainer(), 'ClientRecurring', $clientId),
            'numContacts'     => Business\Client\Utils::getNumberOf($this->getContainer(), 'ClientContact', $clientId),
            'numNotes'        => Business\Client\Utils::getNumberOf($this->getContainer(), 'ClientNote', $clientId),
            'numProducts'     => Business\Client\Utils::getNumberOf($this->getContainer(), 'ClientProduct', $clientId),
            'totalIncome'     => $this->get('app_admin.helper.formatter')->format(Business\Client\Utils::getTotalIncome($this->getContainer(), $clientId), 'money'),
            'recentEmails'    => null,
            'signupDate'      => $this->get('app_admin.helper.formatter')->format(Business\Client\Utils::getSignupDate($this->getContainer(), $clientId), 'date')
        );


        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl($this->configuration['base_route'] . '_list'), 'title' => $this->configuration['grid_title']),
            array('href' => '#', 'title' => 'View client')
        );

        $data['emails_grid'] = array(
            'title'     => 'Emails',
            'jsId'      => 'EmailGrid',
            'readUrl'   => $this->generateUrl('app_admin_client_email_read', $this->get('request')->query->all()),
            'createUrl' => '#',
            'editUrl'   => '#',
            'deleteUrl' => '#'
        );

        return $this->render($this->configuration['base_view'] . ':view.html.twig', $data);
    }

    public function importAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'import');

        $data['saveUrl']          = $this->get('router')->generate('app_admin_upload_save');
        $data['removeUrl']        = $this->get('router')->generate('app_admin_upload_remove');
        $data['importProceedUrl'] = $this->get('router')->generate('app_admin_client_import_proceed');

        $data['grid'] = array(
            'title'     => 'Client import',
            'readUrl'   => $this->get('router')->generate('app_admin_client_import_read'),
            'createUrl' => '#',
            'editUrl'   => '#',
            'deleteUrl' => '#'
        );

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl($this->configuration['base_route'] . '_list'), 'title' => $this->configuration['grid_title']),
            array('href' => '#', 'title' => 'Import client')
        );

        return $this->render('AppAdminBundle:Client:import.html.twig', $data);
    }

    public function importReadAction()
    {
        $gridHandler = $this->get('app_admin.business.client.import_grid_handler');
        $kendoGrid   = $this->get('app_admin.helper.kendo_grid');

        return $kendoGrid->handle($gridHandler);
    }

    public function importProceedAction()
    {
        $handler = $this->get('app_admin.business.client.import_process');
        $data    = $handler->doImport();
        $this->executeFunction('preProcess', $data, 'import_proceed');
        $data['importUrl'] = $this->generateUrl('app_admin_client_import');

        return $this->render('AppAdminBundle:Client:import_result.html.twig', $data);
    }

    public function orderAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'order');

        $handler = $this->get('app_admin.business.client.order_handler');
        $handler->execute();

        $data['form'] = $this->get('app_admin.helper.form')->buildCreateFormView($handler, array('route' => 'app_admin_client_order', $this->get('request')->query->all()));

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl($this->configuration['base_route'] . '_list'), 'title' => $this->configuration['grid_title']),
            array('href' => '#', 'title' => 'Add order')
        );

        $this->executeFunction('postProcess', $data, 'order');

        if ($this->viewTemplate !== null) {
            return $this->render($this->viewTemplate, $data);
        }
        return $this->render($this->configuration['base_view'] . ':order.html.twig', $data);
    }
    public function statementAction(Request $request)
    {
        $data = array();

        $em = $this->getDoctrine()->getManager();
        /** @var ClientInvoice $invoice */
        $invoices = $em->getRepository('AppClientBundle:ClientInvoice')->findBy(['idClient' => $request->query->get('id')]);

        if(!$invoices) $invoices = array();

        $payments = array();
        foreach($invoices as $invoice){
            $payments = array_merge($payments, $em->getRepository('AppClientBundle:ClientPayment')->findBy(['idInvoice' => $invoice->getId()]));
        }

        Business\Client\StatementGenerator::prepareStatementPage($this, $data, $invoices, $payments, 'pdf');

        $data['paymentsGateways'] = $this->getDoctrine()->getManager()->getRepository('AppPaymentsBundle:PaymentGateway')->findAll();
        list ($subdomain) = explode ('.', $request->getHttpHost());
        $data['subdomain'] = $subdomain;

        $resp = new Response();

        $logo = $data['config']->getLogo();
        $img = substr($logo, 0, strpos($logo, '?'));
        $data['config']->setLogo($img);
        $html   = $this->get('templating')->render('AppAdminBundle:Statement:view_pdf.html.twig', $data);

        $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');

        $resp->headers->set('Content-Type', 'application/pdf; charset=UTF-8');
        $resp->headers->set('Content-Disposition', 'inline; filename=invoice.pdf');

        $resp->setContent($snappy->getOutputFromHtml($html));

        return $resp;
    }
}
