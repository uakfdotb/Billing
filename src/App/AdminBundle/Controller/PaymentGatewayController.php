<?php

namespace App\AdminBundle\Controller;

use App\PaymentsBundle\Entity\PaymentGateway;
use App\PaymentsBundle\Form\PaymentGatewayType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


use App\AdminBundle\Business as AdminBusiness;

class PaymentGatewayController extends BaseController
{

    public function preProcess(&$data, $action)
    {
        $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
            'headerMenuSelected' => 'gateways'
        ));
        $data['breadscrum'] = array();
        $data['form'] = array();
        $data['grid'] = array(
            'title'     => 'Payment gateways',
            'readUrl'   => $this->generateUrl('app_admin_payment_gateway_list', $this->get('request')->query->all()),
            'createUrl' => $this->generateUrl('app_admin_payment_gateway_new', $this->get('request')->query->all()),
            'editUrl'   => $this->generateUrl('app_admin_payment_gateway_list', $this->get('request')->query->all()),
            'deleteUrl' => $this->generateUrl('app_admin_payment_gateway_list', $this->get('request')->query->all())
        );
        $this->get('app_admin.helper.billr_application')->checkPermission('admin/payment-gateways');
    }

    public function listAction(Request $request)
    {
        // handle edit
        if ($request->query->has('id')) {
            return $this->redirect($this->generateUrl('app_admin_payment_gateway_edit', array(
                'id' => $request->query->get('id')
            )));
        }
        // handle delete
        if ($request->isMethod('post') && $request->request->has('models')) {
            $models = $request->request->get('models');
            foreach ($models as $model) {
                $this->deleteAction($model['id']);
            }
        }
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_GATEWAY_LIST);
        $data = array();
        $this->executeFunction('preProcess', $data, 'list');
        $gridHandler = $this->get('app_admin.business.payment_gateway.grid_handler');
        $gridHandler->setPager(0, 100);
        $gridHandler->setSort('id', 'DESC');
        $data['grid'] += array(
            'data' => $gridHandler->getResultArray()
        );
        #$data['gatewayItemUrl'] = $this->generateUrl('app_admin_payment_gateway_item_list');
        if ($request->isXmlHttpRequest()) {
            $kendoGrid = $this->get('app_admin.helper.kendo_grid');
            return $kendoGrid->handle($gridHandler);
        } else {
            return $this->render('AppAdminBundle:PaymentGateway:list.html.twig', $data);
        }
    }

    public function newAction(Request $request)
    {
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_GATEWAY_NEW);
        $data = array();
        $this->executeFunction('preProcess', $data, 'new');


        if ($request->query->has('gw')) {
            $paymentGateway = new PaymentGateway();
            $paymentGateway->setType($request->query->get('gw'));
        } else {
            return new RedirectResponse($this->generateUrl('app_admin_payment_gateway_new', array('gw' => '_paypal')));
        }
        $data['form'] = $this->createForm(
            new PaymentGatewayType($this->get('payments.payment_manager')),
            $paymentGateway
        )->createView();

        return $this->render('AppAdminBundle:PaymentGateway:new.html.twig', $data);
    }

    public function createAction(Request $request)
    {
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_GATEWAY_CREATE);
        $data = array();
        $this->executeFunction('preProcess', $data, 'create');

        if ($request->isMethod('post')) {
            $paymentGateway = new PaymentGateway();
            $paymentGateway->setSafeCredentials($request->request->get('payment_gateway')['credentials']);
            $paymentGateway->setType($request->request->get('payment_gateway')['type']);

            $class   = sprintf('\\App\\AdminBundle\\Helper\\Gateway\\%s', ucfirst(str_replace('_', '', $paymentGateway->getType())));
            $errors  = call_user_func(array($class, 'verifyCredentials'), $paymentGateway->getSafeCredentials());

            if (!$errors) {
                $this->get('payments.payment_manager')->save($paymentGateway);
                return $this->redirect($this->generateUrl('app_admin_payment_gateway_list'));
            } else {
                foreach($errors as $error) $form->addError($error);
            }
        } else {
            return $this->redirect($this->generateUrl('app_admin_payment_gateway_new'));
        }

        $data['form'] = $form->createView();
        return $this->render('AppAdminBundle:PaymentGateway:new.html.twig', $data);
    }

    public function editAction($id)
    {
        $paymentGateway = $this->findGatewayOr404($id);

        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_GATEWAY_NEW);
        $data = array(
            'entity' => $paymentGateway
        );
        $this->executeFunction('preProcess', $data, 'edit');

        $data['form'] = $this->createForm(
            new PaymentGatewayType($this->get('payments.payment_manager')),
            $paymentGateway
        )->createView();

        return $this->render('AppAdminBundle:PaymentGateway:edit.html.twig', $data);
    }

    public function updateAction(Request $request, $id)
    {
        $paymentGateway = $this->findGatewayOr404($id);

        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_GATEWAY_NEW);
        $data = array(
            'entity' => $paymentGateway
        );
        $this->executeFunction('preProcess', $data, 'update');

        $form = $this->createForm(
            new PaymentGatewayType($this->get('payments.payment_manager')),
            $paymentGateway
        );

        if ($request->isMethod('post')) {
            $paymentGateway->setSafeCredentials($request->request->get('payment_gateway')['credentials']);

            $class   = sprintf('\\App\\AdminBundle\\Helper\\Gateway\\%s', ucfirst(str_replace('_', '', $paymentGateway->getType())));
            $errors  = call_user_func(array($class, 'verifyCredentials'), $paymentGateway->getSafeCredentials());

            if (!$errors) {
                $this->get('payments.payment_manager')->save($paymentGateway);
                return $this->redirect($this->generateUrl('app_admin_payment_gateway_edit', array(
                    'id' => $paymentGateway->getId()
                )));
            } else {
                foreach($errors as $error) $form->addError($error);
            }
        }

        $data['form'] = $form->createView();

        return $this->render('AppAdminBundle:PaymentGateway:edit.html.twig', $data);
    }

    public function deleteAction($id)
    {
        $paymentGateway = $this->findGatewayOr404($id);

        $this->get('payments.payment_manager')->remove($paymentGateway);

        return $this->redirect($this->generateUrl('app_admin_payment_gateway_list'));
    }

    private function findGatewayOr404($id)
    {
        $gateway = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppPaymentsBundle:PaymentGateway')
            ->find($id);

        if (!$gateway) {
            throw $this->createNotFoundException();
        }

        return $gateway;
    }
}