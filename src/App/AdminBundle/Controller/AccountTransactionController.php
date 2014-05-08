<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use App\AdminBundle\Business;

class AccountTransactionController extends InlineCrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'bank'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('bank/transfer');
    }

    public function postList(&$data, $action)
    {
        parent::postList($data, $action);

        $account         = $this->get('app_admin.helper.doctrine')->findOneByRequestId('AppClientBundle:Account');
        $data['account'] = $account;

        $data['getBalanceUrl'] = $this->generateUrl('app_admin_account_get_balance');

        // Add invoice edit form
        $handler = $this->get('app_admin.business.account.edit_handler');
        $handler->execute();
        $data['form'] = $this->get('app_admin.helper.form')->buildEditFormView($handler, array('route' => 'app_admin_account_transaction_list'));

        // Breadscrum
        $data['breadscrum'] = array(
            array('href' => $this->generateUrl('app_admin_dashboard_list'), 'title' => 'Loading Deck'),
            array('href' => $this->generateUrl('app_admin_account_list'), 'title' => 'Account List'),
            array('href' => '#', 'title' => 'View account')
        );
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Account Transaction',
            'base_route'     => 'app_admin_account_transaction',
            'base_view'      => 'AppAdminBundle:AccountTransaction',
            'grid_title'     => 'View Account',
            'grid_handler'   => 'app_admin.business.account_transaction.grid_handler',
            'create_handler' => 'app_admin.business.account_transaction.create_handler',
            'edit_handler'   => 'app_admin.business.account_transaction.edit_handler',
            'delete_handler' => 'app_admin.business.account_transaction.delete_handler'
        );
    }
}
