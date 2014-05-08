<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class OrderController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('automation/orders');
        $this->checkAutomationStatus();
    }

    public function preEdit(&$data, $action)
    {
        $doctrine = $this->container->get('doctrine');
        $mcrypt   = $this->get('app_admin.helper.mcrypt');

        $data['order']   = $this->container->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:ProductOrder');
        $data['maxmind'] = $data['order']->getMaxmindData();
        $data['orderStatus'] = Business\Order\Constants::getOrderStatus()[$data['order']->getStatus()];

        // Get ClientProduct
        $data['product']  = $doctrine->getRepository('AppClientBundle:ClientProduct')->findOneById($data['order']->getClientProduct());
        $data['username'] = $mcrypt->decrypt($data['product']->getEncryptedUsername());
        $data['password'] = $mcrypt->decrypt($data['product']->getEncryptedPassword());

        // Get ClientInvoice
        $data['invoice']  = $doctrine->getRepository('AppClientBundle:ClientInvoice')->findOneById($data['order']->getIdInvoice());
        $data['invoiceStatus'] = Business\Invoice\Constants::getInvoiceStatuses()[$data['invoice']->getStatus()];
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Order',
            'base_route'     => 'app_admin_order',
            'base_view'      => 'AppAdminBundle:Order',
            'grid_title'     => 'Order List',
            'grid_handler'   => 'app_admin.business.order.grid_handler',
            'create_handler' => 'app_admin.business.order.create_handler',
            'edit_handler'   => 'app_admin.business.order.edit_handler',
            'delete_handler' => 'app_admin.business.order.delete_handler'
        );
    }

    public function changeStatusAction($idOrder)
    {
        $data  = $this->getRequest()->request->all();
        $order = $this->getDoctrine()->getRepository('AppClientBundle:ProductOrder')->findOneById($idOrder);
        $mgr   = $this->getDoctrine()->getManager();
        switch($data['submit'])
        {
            case 'accept':
                $message = $this->get('app_admin.helper.automation_helper')->handlePostAccepted($order);
                break;
            case 'pending':
                $order->setStatus(Business\Order\Constants::ORDER_STATUS_PROCESSING);
                $mgr->persist($order);
                $mgr->flush();
                $message = 'success';
                break;
            case 'fraud':
                $order->setStatus(Business\Order\Constants::ORDER_STATUS_FRAUDULENT);
                $mgr->persist($order);
                $mgr->flush();
                $message = 'success';
                break;
            case 'cancel':
                $order->setStatus(Business\Order\Constants::ORDER_STATUS_CANCELED);
                $mgr->persist($order);
                $mgr->flush();
                $message = 'success';
                break;
            case 'delete':
                $mgr->remove($order);
                $mgr->flush();
                return new RedirectResponse($this->generateUrl('app_admin_client_view', array('id' => $order->getIdClient())));
            default:
                $message = "Action not recognised";
                break;
        }

        return new RedirectResponse($this->generateUrl('app_admin_order_edit', array('id' => $idOrder, 'message' => ucfirst($message))));
    }
}
