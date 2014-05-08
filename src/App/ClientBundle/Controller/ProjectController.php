<?php
namespace App\ClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use App\AdminBundle\Business as AdminBusiness;

class ProjectController extends BaseController
{
    public function preProcess(&$data, $action)
    {
        if (in_array($action, array('list', 'create', 'edit', 'detail'))) {
            $this->get('app_client.helper.billr_application_client')->buildViewHeader($data, array(
                'headerMenuSelected' => 'projects'
            ));
        }
        $this->get('app_client.helper.billr_application_client')->checkPermission(AdminBusiness\ClientContact\Constants::PAGE_PROJECT);
    }

    public function listAction()
    {
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_PROJECT_LIST);

        $data = array();
        $this->executeFunction('preProcess', $data, 'list');

        $gridHandler = $this->get('app_client.business.project.grid_handler');
        $gridHandler->setPager(0, 100);
        $gridHandler->setSort('id', 'DESC');
        $data['grid'] = array(
            'data' => $gridHandler->getResultArray()
        );

        $data['projectDetailUrl'] = $this->generateUrl('app_client_project_detail');

        return $this->render('AppClientBundle:Project:list.html.twig', $data);
    }

    public function detailAction()
    {
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_PROJECT_VIEW);

        $data = array();
        $this->executeFunction('preProcess', $data, 'detail');

        $project         = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientProject');
        $data['project'] = $project;

        $gridHandler = $this->get('app_client.business.project.tracking_grid_handler');
        $gridHandler->setPager(0, 100);
        $gridHandler->setSort('start', 'DESC');
        $data['tracking_grid'] = array('data' => $gridHandler->getResultArray());

        $gridHandler = $this->get('app_client.business.project.attachment_grid_handler');
        $gridHandler->setPager(0, 100);
        $gridHandler->setSort('timestamp', 'DESC');
        $data['attachment_grid'] = array('data' => $gridHandler->getResultArray());

        $gridHandler = $this->get('app_client.business.project.task_grid_handler');
        $gridHandler->setPager(0, 100);
        $gridHandler->setSort('id', 'DESC');
        $data['task_grid'] = array('data' => $gridHandler->getResultArray());

        return $this->render('AppClientBundle:Project:detail.html.twig', $data);
    }
}
