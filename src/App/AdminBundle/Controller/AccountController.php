<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class AccountController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'bank'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('bank/accounts');
    }

    public function postList(&$data, $action)
    {
        $data['accountTransactionListUrl'] = $this->generateUrl('app_admin_account_transaction_list');
        $data['grid']['editUrl']           = $this->generateUrl('app_admin_account_transaction_list');
        $data['transferUrl']               = $this->generateUrl('app_admin_account_transfer');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Account',
            'base_route'     => 'app_admin_account',
            'base_view'      => 'AppAdminBundle:Account',
            'grid_title'     => 'Account List',
            'grid_handler'   => 'app_admin.business.account.grid_handler',
            'create_handler' => 'app_admin.business.account.create_handler',
            'edit_handler'   => 'app_admin.business.account.edit_handler',
            'delete_handler' => 'app_admin.business.account.delete_handler'
        );
    }

    public function transferAction()
    {
        $this->get('app_admin.helper.billr_application')->checkPermission('bank/transfer');

        $data = array();
        $this->executeFunction('preProcess', $data, 'create');
        $handler = $this->get('app_admin.business.account.transfer_handler');
        $handler->execute();

        $data['form'] = $this->get('app_admin.helper.form')->buildCreateFormView($handler, array('route' => 'app_admin_account_transfer'));

        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_account_list'), 'title' => 'Account List'),
            array('href' => '#', 'title' => 'Transfer')
        );

        return $this->render('AppAdminBundle:Account:transfer.html.twig', $data);
    }

    public function getBalanceAction()
    {
        $idAccount = $this->get('request')->query->get('idAccount', 0);
        $account   = $this->get('app_admin.helper.doctrine')->findOneById('AppClientBundle:Account', $idAccount);
        if ($account) {
            return new Response(number_format($account->getBalance(), 2));
        }
        return new Response('Error');
    }
}
