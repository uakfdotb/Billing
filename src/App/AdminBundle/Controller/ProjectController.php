<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class ProjectController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('customer/projects');
    }

    public function postList(&$data, $action)
    {
        $data['grid']['projectTrackingListUrl'] = $this->generateUrl('app_admin_project_tracking_list');
        $data['grid']['projectTimerUrl']        = $this->generateUrl('app_admin_project_timer');
        $data['grid']['editUrl']                = $this->generateUrl('app_admin_project_tracking_list');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Project',
            'base_route'     => 'app_admin_project',
            'base_view'      => 'AppAdminBundle:Project',
            'grid_title'     => 'Project List',
            'grid_handler'   => 'app_admin.business.project.grid_handler',
            'create_handler' => 'app_admin.business.project.create_handler',
            'edit_handler'   => 'app_admin.business.project.edit_handler',
            'delete_handler' => 'app_admin.business.project.delete_handler'
        );
    }

    public function timerAction()
    {
        $timer     = $this->getRequest()->query->get('timer', null);
        $idProject = $this->getRequest()->query->get('id', 0);
        $resp      = false;
        if ($timer == 'start') {
            $resp = Business\Project\Utils::startProjectTracking($this, $idProject);
        } else if ($timer == 'stop') {
            $resp = Business\Project\Utils::stopProjectTracking($this, $idProject);
        }
        return new Response($resp ? "ok" : "failed");
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

        if ($this->getRequest()->getMethod() == 'POST' /*&& $handler->newId != 0*/) {
            $editUrl = $this->generateUrl('app_admin_project_edit', array('id' => 1));
            return $this->redirect($editUrl);
        }

        return $this->render($this->configuration['base_view'] . ':create.html.twig', $data);
    }
}
