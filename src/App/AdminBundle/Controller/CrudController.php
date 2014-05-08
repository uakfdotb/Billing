<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class CrudController extends BaseController
{
    public $configuration = array();

    public function getConfiguration()
    {
        return array(
            'base_route'     => 'app_admin_base',
            'base_view'      => 'AppAdminBundle:Client',
            'grid_title'     => '',
            'grid_handler'   => '',
            'create_handler' => '',
            'edit_handler'   => '',
            'delete_handler' => ''
        );
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    public function preProcess(&$data, $action)
    {
        $this->configuration = $this->getConfiguration();

        $data['mappingUrl'] = $this->generateUrl('app_admin_mapping_getKendoMapping');
    }

    public function listAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'list');
        $this->executeFunction('preList', $data, 'list');

        $data['grid'] = array(
            'title'     => $this->configuration['grid_title'],
            'readUrl'   => $this->generateUrl($this->configuration['base_route'] . '_read', $this->get('request')->query->all()),
            'createUrl' => $this->generateUrl($this->configuration['base_route'] . '_create', $this->get('request')->query->all()),
            'editUrl'   => $this->generateUrl($this->configuration['base_route'] . '_edit', $this->get('request')->query->all()),
            'deleteUrl' => $this->generateUrl($this->configuration['base_route'] . '_delete', $this->get('request')->query->all()),
            'exportUrl' => $this->generateUrl($this->configuration['base_route'] . '_export', $this->get('request')->query->all()),
        );

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => '#', 'title' => $this->configuration['grid_title'])
        );

        $this->executeFunction('postList', $data, 'list');
        $this->executeFunction('postProcess', $data, 'list');

        if ($this->viewTemplate !== null) {
            return $this->render($this->viewTemplate, $data);
        }
        return $this->render($this->configuration['base_view'] . ':list.html.twig', $data);
    }

    public function readAction()
    {
        $data = array();

        $this->executeFunction('preProcess', $data, 'read');
        $this->executeFunction('preRead', $data, 'read');

        $gridHandler = $this->get($this->configuration['grid_handler']);
        $kendoGrid   = $this->get('app_admin.helper.kendo_grid');

        $this->executeFunction('postRead', $data, 'read');
        $this->executeFunction('postProcess', $data, 'read');

        return $kendoGrid->handle($gridHandler);
    }

    public function exportAction()
    {
        $data = array();

        $this->executeFunction('preProcess', $data, 'export');
        $this->executeFunction('preRead', $data, 'export');

        $gridHandler = $this->get($this->configuration['grid_handler']);
        $kendoGrid   = $this->get('app_admin.helper.kendo_grid');

        $this->executeFunction('postRead', $data, 'export');
        $this->executeFunction('postProcess', $data, 'export');

        return $kendoGrid->handleExportCsv($gridHandler);
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

        if ($this->viewTemplate !== null) {
            return $this->render($this->viewTemplate, $data);
        }
        return $this->render($this->configuration['base_view'] . ':create.html.twig', $data);
    }

    public function editAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'edit');
        $this->executeFunction('preEdit', $data, 'edit');

        $handler = $this->get($this->configuration['edit_handler']);
        $handler->execute();

        $data['form']       = $this->get('app_admin.helper.form')->buildEditFormView($handler, array('route' => $this->configuration['base_route'] . '_edit'));
        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl($this->configuration['base_route'] . '_list'), 'title' => $this->configuration['grid_title']),
            array('href' => '#', 'title' => 'Edit ' . $this->configuration['title'])
        );

        $this->executeFunction('postEdit', $data, 'edit');
        $this->executeFunction('postProcess', $data, 'edit');

        if ($this->viewTemplate !== null) {
            return $this->render($this->viewTemplate, $data);
        }
        return $this->render($this->configuration['base_view'] . ':edit.html.twig', $data);
    }

    public function deleteAction()
    {
        $data = array();
        $this->executeFunction('preProcess', $data, 'delete');
        $this->executeFunction('preDelete', $data, 'delete');

        $handler = $this->get($this->configuration['delete_handler']);
        $handler->execute();

        $this->executeFunction('postDelete', $data, 'delete');
        $this->executeFunction('postProcess', $data, 'delete');

        return new Response('Done');
    }

    public function checkAutomationStatus()
    {
        return true;
    }
}
