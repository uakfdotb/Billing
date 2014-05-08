<?php
namespace App\AdminBundle\Business\Search;

class Utils
{
    public static function search($container, $terms)
    {
        $words = explode(' ', $terms);
        $arr = '';
        $i = 1;
        foreach ($words as $w) {
            $arr['kw' . $i] = trim($w);
            $i++;
        }

        $result = array();
        $result[] = array(
            'name'    => 'ACCOUNT',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:Account', array('name', 'number', 'email'),
                'app_admin_account_edit'
            )
        );
        /*$result[] = array(
            'name'    => 'STAFF',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:Admin', array('firstname', 'lastname', 'email'),
                'app_admin_staff_edit'
            )
        ); */
        $result[] = array(
            'name'    => 'AUTOMATION_GROUPS',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:AutomationGroup', array('name'),
                'app_admin_automation_group_edit'
            )
        );
        $result[] = array(
            'name'    => 'CONTACT',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:ClientContact', array('firstname', 'lastname', 'email'),
                'app_admin_client_contact_edit'
            )
        );
        $result[] = array(
            'name'    => 'CLIENT',
            'records' => Utils::searchTable($container, $arr, 'AppUserBundle:User', array('firstname', 'lastname', 'company', 'address1', 'address2', 'city', 'state', 'postcode', 'phone', 'email'),
                'app_admin_client_edit'
            )
        );
        $result[] = array(
            'name'    => 'ESTIMATE',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:ClientEstimate', array('subject', 'notes', 'number'),
                'app_admin_estimate_item_list'
            )
        );
        $result[] = array(
            'name'    => 'ESTIMATE_ITEMS',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:ClientEstimateItem', array('description'),
                'app_admin_estimate_item_list',
                'idEstimate'
            )
        );
        $result[] = array(
            'name'    => 'INVOICE',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:ClientInvoice', array('subject', 'notes', 'number'),
                'app_admin_invoice_item_list'
            )
        );
        $result[] = array(
            'name'    => 'INVOICE_ITEMS',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:ClientInvoiceItem', array('description'),
                'app_admin_invoice_item_list',
                'idInvoice'
            )
        );
        $result[] = array(
            'name'    => 'PAYMENTS',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:ClientPayment', array('transaction'),
                'app_admin_estimate_item_list',
                'idEstimate'
            )
        );
        $result[] = array(
            'name'    => 'PROJECTS',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:ClientProject', array('name'),
                'app_admin_project_edit'
            )
        );
        $result[] = array(
            'name'    => 'PROJECT_ATTACHMENTS',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:ClientProjectAttachment', array('name', 'description'),
                'app_admin_project_edit',
                'idProject'
            )
        );
        $result[] = array(
            'name'    => 'RECURRINGS',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:ClientRecurring', array('subject', 'notes'),
                'app_admin_recurring_edit'
            )
        );
        $result[] = array(
            'name'    => 'RECURRING_ITEMS',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:ClientRecurringItem', array('description'),
                'app_admin_recurring_edit',
                'idRecurring'
            )
        );
        $result[] = array(
            'name'    => 'EMAIL',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:Email', array('name', 'subject'),
                'app_admin_email_edit'
            )
        );
        $result[] = array(
            'name'    => 'PRODUCT',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:Product', array('name'),
                'app_admin_product_edit'
            )
        );
        $result[] = array(
            'name'    => 'PRODUCT_GROUPS',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:ProductGroup', array('name'),
                'app_admin_product_group_edit'
            )
        );
        $result[] = array(
            'name'    => 'PRODUCT_ORDERS',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:ProductOrder', array('orderNumber'),
                'app_admin_order_edit'
            )
        );
        $result[] = array(
            'name'    => 'SUPPLIERS',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:Supplier', array('firstname', 'lastname', 'company', 'address1', 'address2', 'city', 'state', 'postcode', 'phone', 'email'),
                'app_admin_supplier_edit'
            )
        );
        $result[] = array(
            'name'    => 'SUPPLIER_PURCHASE',
            'records' => Utils::searchTable($container, $arr, 'AppClientBundle:SupplierPurchase', array('name'),
                'app_admin_supplier_purchase_edit'
            )
        );
        return $result;
    }

    public static function searchTable($container, $keywords, $repository, $fields, $viewRoute, $viewId = 'id')
    {
        $em = $container->get('doctrine')->getEntityManager();
        $router = $container->get('router');
        $query = $em->createQueryBuilder();
        $query->select('p')
            ->from($repository, 'p');
        foreach ($fields as $field) {
            foreach ($keywords as $k => $word) {
                $query->orWhere(' (p.' . $field . " LIKE '%" . $word . "%') ");
            }
        }
        $query->setFirstResult(0);
        $query->setMaxResults(10);
        $result = $query->getQuery()->getResult();
        $arr = array();
        foreach ($result as $row) {
            $line = '';
            foreach ($fields as $field) {
                $getMethod = 'get' . ucfirst($field);
                if (method_exists($row, $getMethod)) {
                    $line[] = $row->$getMethod();
                }
            }
            $getIdMethod = 'get' . ucfirst($viewId);
            $id = $row->$getIdMethod();
            $line = implode(' | ', $line);
            $arr[] = array(
                'text' => $line,
                'link' => $router->generate($viewRoute, array('id' => $id))
            );
        }
        return $arr;
    }
}
