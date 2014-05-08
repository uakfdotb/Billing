<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;
use Knp\Snappy\Pdf;

class EstimateController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('customer/estimates');
    }

    public function postList(&$data, $action)
    {
        $data['estimateItemListUrl']       = $this->generateUrl('app_admin_estimate_item_list');
        $data['estimateGetHashUrl']        = $this->generateUrl('app_admin_estimate_get_hash');
        $data['estimateViewClientSideUrl'] = $this->generateUrl('app_admin_estimate_view');
        $data['estimateSendEmailUrl']      = $this->generateUrl('app_admin_estimate_send_email');

        $data['invoiceUrl']      = $this->generateUrl('app_admin_estimate_make_invoice');
        $data['grid']['editUrl'] = $this->generateUrl('app_admin_estimate_item_list');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Estimate',
            'base_route'     => 'app_admin_estimate',
            'base_view'      => 'AppAdminBundle:Estimate',
            'grid_title'     => 'Estimate List',
            'grid_handler'   => 'app_admin.business.estimate.grid_handler',
            'create_handler' => 'app_admin.business.estimate.create_handler',
            'edit_handler'   => 'app_admin.business.estimate.edit_handler',
            'delete_handler' => 'app_admin.business.estimate.delete_handler'
        );
    }

    public function sendEmailAction()
    {
        $estimate = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientEstimate');
        $msg      = $this->getRequest()->query->get('msg', '');
        if ($estimate != false && ($msg == 'estimate' || $msg == 'reminder' || $msg == 'overdue' || $msg == 'receipt')) {
            $res = Business\Estimate\Utils::sendEstimateEmail($this, $estimate, $msg);
            if ($res === true) {
                if ($msg == 'estimate') {
                    $estimate->setStatus(Business\Estimate\Constants::STATUS_SENT);
                    $this->getDoctrine()->getEntityManager()->flush();
                }

                return new Response('ok');
            } else {
                return new Response($res);
            }
        }
        return new Response('error');
    }

    public function makeInvoiceAction()
    {
        $id = $this->get('request')->query->get('id', 0);
        if (Business\Estimate\Utils::makeInvoice($this, $id)) {
            return new Response('ok');
        }
        return new Response('failed');
    }

    public function createAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'create');
        $this->executeFunction('preCreate', $data, 'create');

        $handler = $this->get($this->configuration['create_handler']);
        $handler->execute();

        $data['form'] = $this->get('app_admin.helper.form')->buildCreateFormView($handler, array('route' => $this->configuration['base_route'] . '_create'));

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl($this->configuration['base_route'] . '_list'), 'title' => $this->configuration['grid_title']),
            array('href' => '#', 'title' => 'Add ' . $this->configuration['title'])
        );

        $this->executeFunction('postCreate', $data, 'create');
        $this->executeFunction('postProcess', $data, 'create');

        if ($this->getRequest()->getMethod() == 'POST' && $handler->newId != 0) {
            $editUrl = $this->generateUrl('app_admin_estimate_item_list', array('id' => $handler->newId));
            return $this->redirect($editUrl);
        }

        return $this->render($this->configuration['base_view'] . ':create.html.twig', $data);
    }


    public function viewAction()
    {
        $data = array();

        $estimate = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientEstimate');
        $mode     = $this->getRequest()->get('mode', '');

        $routes = array(
            'pdf' => 'app_admin_estimate_view'
        );
        Business\Estimate\Utils::prepareEstimatePage($this, $data, $estimate, $mode, $routes);

        $html   = $this->get('templating')->render('AppAdminBundle:Estimate:view_pdf.html.twig', $data);
        $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');


        $resp = new Response();
        $resp->headers->set('Content-Type', 'application/pdf; charset=UTF-8');
        $resp->headers->set('Content-Disposition', 'inline; filename=estimate.pdf');
        $resp->setContent($snappy->getOutputFromHtml($html));

        return $resp;
    }
}
