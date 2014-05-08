<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class RecurringItemController extends InlineCrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('customer/recurrings');
    }

    public function postList(&$data, $action)
    {
        parent::postList($data, $action);

        $data['projectTypesListUrl'] = $this->generateUrl('app_admin_mapping_getKendoMapping', array('code' => 'project_type', 'withNull' => 1));

        $recurring         = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientRecurring');
        $data['recurring'] = $recurring;

	 $data['singlePageGrid'] = true;

        // Add recurring edit form
        $handler = $this->get('app_admin.business.recurring.edit_handler');
        $handler->execute();
        $data['form'] = $this->get('app_admin.helper.form')->buildEditFormView($handler, array('route' => 'app_admin_recurring_item_list'));

        // Breadscrum
        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_recurring_list'), 'title' => 'Recurring List'),
            array('href' => '#', 'title' => 'View recurring')
        );
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Recurring item',
            'base_route'     => 'app_admin_recurring_item',
            'base_view'      => 'AppAdminBundle:RecurringItem',
            'grid_title'     => 'View Recurring Invoice',
            'grid_handler'   => 'app_admin.business.recurring_item.grid_handler',
            'create_handler' => 'app_admin.business.recurring_item.create_handler',
            'edit_handler'   => 'app_admin.business.recurring_item.edit_handler',
            'delete_handler' => 'app_admin.business.recurring_item.delete_handler'
        );
    }
}
