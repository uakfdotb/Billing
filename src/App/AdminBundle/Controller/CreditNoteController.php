<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class CreditNoteController extends CrudController
{
    public function preProcess(&$data, $action)
    {
        parent::preProcess($data, $action);

        if (in_array($action, array('list', 'create', 'edit'))) {
            $this->get('app_admin.helper.billr_application')->buildViewHeader($data, array(
                'headerMenuSelected' => 'customer'
            ));
        }
        $this->get('app_admin.helper.billr_application')->checkPermission('customer/credit_note');
    }

    public function getConfiguration()
    {
        return array(
            'title'          => 'Credit Note',
            'base_route'     => 'app_admin_credit_note',
            'base_view'      => 'AppAdminBundle:CreditNote',
            'grid_title'     => 'Credit Note List',
            'grid_handler'   => 'app_admin.business.credit_note.grid_handler',
            'create_handler' => 'app_admin.business.credit_note.create_handler',
            'edit_handler'   => 'app_admin.business.credit_note.edit_handler',
            'delete_handler' => 'app_admin.business.credit_note.delete_handler'
        );
    }
}
