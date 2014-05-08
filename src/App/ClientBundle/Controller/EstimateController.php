<?php

namespace App\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business as AdminBusiness;

class EstimateController extends BaseController
{
    public function preProcess(&$data, $action)
    {
        if (in_array($action, array('list', 'create', 'edit', 'view_client_site'))) {
            $this->get('app_client.helper.billr_application_client')->buildViewHeader($data, array(
                'headerMenuSelected' => 'estimates'
            ));
        }
        //$this->get('app_client.helper.billr_application_client')->checkPermission(AdminBusiness\ClientContact\Constants::PAGE_ESTIMATE);
    }

    public function viewAction()
    {
        $data = array();

        $estimate = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientEstimate');
        $mode     = $this->getRequest()->get('mode', '');

        $routes = array(
            'pdf' => 'app_client_estimate_view'
        );
        AdminBusiness\Estimate\Utils::prepareEstimatePage($this, $data, $estimate, $mode, $routes);

        $msg = 'Estimate ID: ' . $estimate->getNumber();
        if ($mode == 'pdf') {
            $msg .= ' (Download pdf)';
        }
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_ESTIMATE_VIEW, $msg);

        if ($mode == 'pdf') {
            $html   = $this->get('templating')->render('AppAdminBundle:Estimate:view_pdf.html.twig', $data);
            $dompdf = $this->get('slik_dompdf');
            $dompdf->getpdf($html);

            $resp = new Response();
            $resp->headers->set('Content-Type', 'application/pdf; charset=UTF-8');
            $resp->headers->set('Content-Disposition', 'inline; filename=estimate.pdf');
            $resp->setContent($dompdf->output());

            return $resp;
        }

        return $this->render('AppAdminBundle:Estimate:view.html.twig', $data);
    }
    public function listAction()
    {
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_ESTIMATE_VIEW);

        $data = array();
        $this->executeFunction('preProcess', $data, 'list');

        $gridHandler = $this->get('app_client.business.estimate.grid_handler');
        $gridHandler->setPager(0, 100);
        $gridHandler->setSort('id', 'DESC');
        $data['grid'] = array(
            'data' => $gridHandler->getResultArray()
        );

        $data['estimateView'] = $this->generateUrl('app_client_estimate_view');

        return $this->render('AppClientBundle:Estimate:list.html.twig', $data);
    }
}

