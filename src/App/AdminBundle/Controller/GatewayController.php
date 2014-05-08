<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class GatewayController extends BaseController
{
    public function editAction()
    {
        throw $this->createNotFoundException();
        $data = array();
        $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
            'headerMenuSelected' => 'admin'
        ));
        $this->get('app_admin.helper.billr_application')->checkPermission('admin/gateways');

        $handler = $this->get('app_admin.business.gateway.edit_handler');
        $handler->execute();
        $data['form'] = $this->get('app_admin.helper.form')->buildEditFormView($handler, array('route' => 'app_admin_gateway_edit'));

        // Breadscrum
        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => '#', 'title' => 'Gateway')
        );

        return $this->render('AppAdminBundle:Setting:edit_gateway.html.twig', $data);
    }
}
