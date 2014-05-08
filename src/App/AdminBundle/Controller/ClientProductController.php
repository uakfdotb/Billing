<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\AdminBundle\Business;

class ClientProductController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('customer/clients');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Client Product',
            'base_route'     => 'app_admin_client_product',
            'base_view'      => 'AppAdminBundle:ClientProduct',
            'grid_title'     => 'Client Product List',
            'grid_handler'   => 'app_admin.business.client_product.grid_handler',
            'create_handler' => 'app_admin.business.client_product.create_handler',
            'edit_handler'   => 'app_admin.business.client_product.edit_handler',
            'delete_handler' => 'app_admin.business.client_product.delete_handler'
        );
    }

    public function postCreate(&$data, $action)
    {
        $idClient           = $this->getRequest()->get('id', 0);
        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_client_list'), 'title' => 'Client List'),
            array('href' => $this->generateUrl('app_admin_client_edit', array('id' => $idClient)), 'title' => 'Edit client'),
            array('href' => '#', 'title' => 'Add product')
        );
    }

    public function postEdit(&$data, $action)
    {
        $idNote   = $this->getRequest()->get('id', 0);
        $contact  = $this->getDoctrine()->getRepository('AppClientBundle:ClientProduct')->findOneById($idNote);
        $idClient = $contact->getIdClient();

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_client_list'), 'title' => 'Client List'),
            array('href' => $this->generateUrl('app_admin_client_edit', array('id' => $idClient)), 'title' => 'Edit client'),
            array('href' => '#', 'title' => 'Edit product')
        );
    }

    public function viewAction()
    {
        $data = array();
        $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
            'headerMenuSelected' => 'customer'
        ));

        $mcrypt        = $this->get('app_admin.helper.mcrypt');
        $idCP          = $this->getRequest()->query->get('id');
        $clientProduct = $this->getDoctrine()->getRepository('AppClientBundle:ClientProduct')->findOneById($idCP);
        $data['cp']    = [
            'id'       => $clientProduct->getId(),
            'term'     => Business\Order\Constants::getOrderPaymentTerms()[$clientProduct->getIdPaymentTerm()],
            'ip'       => $clientProduct->getIpAddress(),
            'user'     => $mcrypt->decrypt($clientProduct->getEncryptedUsername()),
            'pass'     => $mcrypt->decrypt($clientProduct->getEncryptedPassword()),
            'server'   => $this->getDoctrine()->getRepository('AppClientBundle:Server')->findOneById($clientProduct->getIdServer())->getName(),
            'cost'     => $clientProduct->getCost(),
            'due'      => $clientProduct->getNextDue(),
            'product'  => $this->getDoctrine()->getRepository('AppClientBundle:Product')->findOneById($clientProduct->getIdProduct())->getName(),
        ];

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_client_list'), 'title' => 'Client List'),
            array('href' => $this->generateUrl('app_admin_client_edit'), 'title' => 'Edit client'),
            array('href' => '#', 'title' => 'View client product')
        );

        return $this->render('AppAdminBundle:ClientProduct:edit.html.twig', $data);
    }

    public function moduleAction($idCP)
    {
        $action = $this->getRequest()->request->get('submit');
        $cp     = $this->getDoctrine()->getRepository('AppClientBundle:ClientProduct')->findOneById($idCP);
        $product= $this->getDoctrine()->getRepository('AppClientBundle:Product')->findOneById($cp->getIdProduct());

        switch($action)
        {
            case 'create':
                $message = $this->get('app_admin.helper.automation_helper')->createProduct($cp, $product);
                break;
            case 'suspend':
                $message = $this->get('app_admin.helper.automation_helper')->suspendClientProduct($cp);
                break;
            case 'terminate':
                $message = $this->get('app_admin.helper.automation_helper')->terminateClientProduct($cp);
                break;
            default:
                $message = "Action not recognised";
                break;
        }

        return new RedirectResponse($this->generateUrl('app_admin_client_product_view', array('id' => $idCP, 'message' => ucfirst($message))));
    }
}
