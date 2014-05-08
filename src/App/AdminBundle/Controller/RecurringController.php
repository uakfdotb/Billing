<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class RecurringController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('customer/recurring');
    }

    public function postList(&$data, $action)
    {
        $data['recurringItemListUrl'] = $this->generateUrl('app_admin_recurring_item_list');
        $data['recurringChartUrl']    = $this->generateUrl('app_admin_recurring_chart');

        $data['grid']['editUrl'] = $this->generateUrl('app_admin_recurring_item_list');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Recurring',
            'base_route'     => 'app_admin_recurring',
            'base_view'      => 'AppAdminBundle:Recurring',
            'grid_title'     => 'Recurring Invoice List',
            'grid_handler'   => 'app_admin.business.recurring.grid_handler',
            'create_handler' => 'app_admin.business.recurring.create_handler',
            'edit_handler'   => 'app_admin.business.recurring.edit_handler',
            'delete_handler' => 'app_admin.business.recurring.delete_handler'
        );
    }

    public function chartAction()
    {
        $query = $this->get('doctrine')->getEntityManager()->createQueryBuilder();
        $query->select('p.idSchedule, COUNT(p) as number')
            ->from('AppClientBundle:ClientRecurring', 'p')
            ->groupBy('p.idSchedule');
        $result = $query->getQuery()->getResult();

        $helperMapping = $this->get('app_admin.helper.mapping');
        $donut         = array();
        foreach ($result as $row) {
            $donut[] = array(
                'status' => $helperMapping->getMappingTitle('recurring_schedule', $row['idSchedule']),
                'number' => $row['number']
            );
        }

        $response = new Response(json_encode($donut, JSON_NUMERIC_CHECK));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function createAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'create');
        $this->executeFunction('preCreate', $data, 'create');

        $handler = $this->get($this->configuration['create_handler']);
        $handler->execute();

        $data['form'] = $this->get('app_admin.helper.form')->buildCreateFormView($handler, array('route' => $this->configuration['base_route'] . '_create'));

        $this->executeFunction('postCreate', $data, 'create');
        $this->executeFunction('postProcess', $data, 'create');

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl($this->configuration['base_route'] . '_list'), 'title' => $this->configuration['grid_title']),
            array('href' => '#', 'title' => 'Add ' . $this->configuration['title'])
        );

        if ($this->getRequest()->getMethod() == 'POST' && $handler->newId != 0) {
            $editUrl = $this->generateUrl('app_admin_recurring_item_list', array('id' => $handler->newId));
            return $this->redirect($editUrl);
        }

        return $this->render($this->configuration['base_view'] . ':create.html.twig', $data);
    }
}
