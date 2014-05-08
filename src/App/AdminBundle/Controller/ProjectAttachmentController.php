<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class ProjectAttachmentController extends CrudController
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


    public function postCreate(&$data, $action)
    {
        $idProject          = $this->getRequest()->get('id', 0);
        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_project_list'), 'title' => 'Project List'),
            array('href' => $this->generateUrl('app_admin_project_tracking_list', array('id' => $idProject)), 'title' => 'Project detail'),
            array('href' => '#', 'title' => 'Add attachment')
        );
    }

    public function postEdit(&$data, $action)
    {
        $idProjectAttachment = $this->getRequest()->get('id', 0);
        $projectAttachment   = $this->getDoctrine()->getRepository('AppClientBundle:ClientProjectAttachment')->findOneById($idProjectAttachment);
        $idProject           = $projectAttachment->getIdProject();

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_project_list'), 'title' => 'Project List'),
            array('href' => $this->generateUrl('app_admin_project_tracking_list', array('id' => $idProject)), 'title' => 'Project detail'),
            array('href' => '#', 'title' => 'Edit attachment')
        );
    }


    public function getConfiguration()
    {
        return array(
            'title'          => 'Project Attachment',
            'base_route'     => 'app_admin_project_attachment',
            'base_view'      => 'AppAdminBundle:ProjectAttachment',
            'grid_title'     => 'View Project',
            'grid_handler'   => 'app_admin.business.project_attachment.grid_handler',
            'create_handler' => 'app_admin.business.project_attachment.create_handler',
            'edit_handler'   => 'app_admin.business.project_attachment.edit_handler',
            'delete_handler' => 'app_admin.business.project_attachment.delete_handler'
        );
    }

    public function downloadAction()
    {
        $file = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ClientProjectAttachmentFile')->getFile();
        $folder = $this->container->getParameter('project_upload_dir');
        $filename = $folder . '/' . $file;

        // Generate response
        $response = new Response();

        // Set headers
        $response->headers->set('Cache-Control', 'private');
        $response->headers->set('Content-type', mime_content_type($filename));
        $response->headers->set('Content-Disposition', 'attachment; filename="' . basename($filename) . '"');
        $response->headers->set('Content-length', filesize($filename));

        // Send headers before outputting anything
        $response->sendHeaders();

        $response->setContent(readfile($filename));

        return $response;
    }
}
