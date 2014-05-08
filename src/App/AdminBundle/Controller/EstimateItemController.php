<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class EstimateItemController extends InlineCrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
    }

    public function postList(&$data, $action)
    {
        parent::postList($data, $action);

        $data['projectTypesListUrl'] = $this->generateUrl('app_admin_mapping_getKendoMapping', array('code' => 'project_type', 'withNull' => 1));
        $data['refundUrl']           = $this->generateUrl('app_admin_estimate_payment_refund');

        $estimate         = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientEstimate');
        $data['estimate'] = $estimate;

        // Add estimate edit form
        $handler = $this->get('app_admin.business.estimate.edit_handler');
        $handler->execute();
        $data['form'] = $this->get('app_admin.helper.form')->buildEditFormView($handler, array('route' => 'app_admin_estimate_item_list'));

        // Set grid to single page
        $data['singlePageGrid'] = true;

        // Breadscrum
        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_estimate_list'), 'title' => 'Estimate List'),
            array('href' => '#', 'title' => 'View estimate')
        );
    }


    public function getConfiguration()
    {
        return array(
            'title'          => 'Estimate Item',
            'base_route'     => 'app_admin_estimate_item',
            'base_view'      => 'AppAdminBundle:EstimateItem',
            'grid_title'     => 'View Estimate',
            'grid_handler'   => 'app_admin.business.estimate_item.grid_handler',
            'create_handler' => 'app_admin.business.estimate_item.create_handler',
            'edit_handler'   => 'app_admin.business.estimate_item.edit_handler',
            'delete_handler' => 'app_admin.business.estimate_item.delete_handler'
        );
    }
}
