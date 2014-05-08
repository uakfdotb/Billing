<?php
namespace App\AdminBundle\Helper;

class BillrApplication
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function buildViewHeader(&$data, $options = array())
    {
        $router = $this->container->get('router');
        $config = $this->container->get('app_admin.helper.common')->getConfig();

        $helperUser = $this->container->get('app_admin.helper.user');

        $data['logoutUrl']    = $router->generate('fos_user_security_logout');
        $data['searchUrl']    = $router->generate('app_admin_search_search');
        $data['uploadUrl']    = $router->generate('app_admin_upload_save');
        $data['dashboardUrl'] = $router->generate('app_admin_dashboard_list');
        $data['config']       = $config;

        $formatter               = $this->container->get('app_admin.helper.formatter');
        $data['currencyCode']    = $formatter->getCurrencyCode();
        $helperMapping           = $this->container->get('app_admin.helper.mapping');
        $data['kendoDateFormat'] = $helperMapping->getMappingTitle('config_kendo_date_picker_format', $config ? $config->getDateFormat() : 'H:i:s d/m/Y');

        $staff            = $helperUser->getUserSession();
        $data['username'] = $staff->getFirstname() . ' ' . $staff->getLastname();

        $data['appLogo'] = $config->getAdminDirectory() . '/../../logo/' . $config->getLogo();

        // New menu
        $menu = array(
            //'dashboard' => array('title' => '', 'class' => 'icon-home', 'href' => $router->generate('app_admin_dashboard_list')),
            'customer'  => array(
                'title' => 'CUSTOMER',
                'class' => 'entypo vcard',
                'menu'  => array(
                    'clients'     => array('title' => 'CLIENTS', 'class' => 'entypo users', 'href' => $router->generate('app_admin_client_list')),
                    'projects'    => array('title' => 'PROJECTS', 'class' => 'entypo calendar', 'href' => $router->generate('app_admin_project_list')),
                    'estimates'   => array('title' => 'ESTIMATES', 'class' => 'entypo line-graph', 'href' => $router->generate('app_admin_estimate_list')),
                    'invoices'    => array('title' => 'INVOICES', 'class' => 'entypo newspaper', 'href' => $router->generate('app_admin_invoice_list')),
                    'recurring'  => array('title' => 'RECURRINGS', 'class' => 'entypo cycle', 'href' => $router->generate('app_admin_recurring_list')),
                    //'tickets'     => array('title' => 'TICKETS', 'class' => '', 'href' => $router->generate('app_admin_ticket_list')),
                    'credit_note' => array('title' => 'CREDIT_NOTES', 'class' => 'entypo docs', 'href' => $router->generate('app_admin_credit_note_list')),
                )
            ),
            'supplier'  => array(
                'title' => 'SUPPLIER',
                'class' => 'icon-folder-close',
                'menu'  => array(
                    'suppliers'          => array('title' => 'SUPPLIERS', 'class' => 'entypo flow-branch', 'href' => $router->generate('app_admin_supplier_list')),
                    'supplier_purchases' => array('title' => 'PURCHASES', 'class' => 'entypo squared-plus', 'href' => $router->generate('app_admin_supplier_purchase_list')),
                    'inventory'          => array('title' => 'INVENTORY', 'class' => 'entypo numbered-list', 'href' => $router->generate('app_admin_physical_item_list')),
                )
            ),
            'bank'      => array(
                'title' => 'BANK',
                'class' => 'icon-money',
                'menu'  => array(
                    'accounts'  => array('title' => 'ACCOUNT', 'class' => 'entypo bag', 'href' => $router->generate('app_admin_account_list')),
                    'transfer' => array('title' => 'TRANSFER', 'class' => 'entypo switch', 'href' => $router->generate('app_admin_account_transfer')),
                )
            ),
            'automation'   => array(
                'title' => 'AUTOMATION',
                'class' => 'entypo rocket',
                'menu'  => array(
                    //'automation_groups' => array('title' => 'AUTOMATION_GROUPS', 'class' => 'entypo rocket', 'href' => $router->generate('app_admin_automation_group_list')),
                    'server_groups' => array('title' => 'SERVER_GROUPS', 'class' => 'entypo layout', 'href' => $router->generate('app_admin_server_group_list')),
                    'servers' => array('title' => 'SERVERS', 'class' => 'entypo cloud', 'href' => $router->generate('app_admin_server_list')),
                    'product_groups'    => array('title' => 'PRODUCT_GROUPS', 'class' => 'entypo box', 'href' => $router->generate('app_admin_product_group_list')),
                    'products'          => array('title' => 'PRODUCTS', 'class' => 'entypo heart', 'href' => $router->generate('app_admin_product_list')),
                    'orders'      => array('title' => 'ORDERS', 'class' => 'entypo cart', 'href' => $router->generate('app_admin_order_list')),
                )
            ),
            'admin'     => array(
                'title' => 'ADMIN',
                'class' => 'icon-cogs',
                'menu'  => array(
                    'staff'            => array('title' => 'STAFF', 'class' => 'entypo user', 'href' => $router->generate('app_admin_staff_list')),
                    'payment-gateways'  => array('title' => 'PAYMENT_GATEWAYS', 'class' => 'entypo globe', 'href' => $router->generate('app_admin_payment_gateway_list')),
                    'settings'          => array('title' => 'SETTING', 'class' => 'entypo flash', 'href' => $router->generate('app_admin_setting_edit')),
                    'logs'              => array('title' => 'LOG', 'class' => 'entypo monitor', 'href' => $router->generate('app_admin_log_list')),
                    //'client_logs'       => array('title' => 'CLIENT_AUDIT', 'class' => '', 'href' => $router->generate('app_admin_client_log_list')),
                    'emails'            => array('title' => 'EMAILS', 'class' => 'entypo paper-plane', 'href' => $router->generate('app_admin_email_list')),
                    'tax'               => array('title' => 'TAX_GROUPS', 'class' => 'entypo new', 'href' => $router->generate('app_admin_tax_list')),
                    'report'            => array('title' => 'REPORT', 'class' => 'entypo print', 'href' => $router->generate('app_admin_report_list')),
                    'permission_group' => array('title' => 'PERMISSION_GROUPS', 'class' => 'entypo vcard', 'href' => $router->generate('app_admin_permission_group_list')),
                )
            ),
        );
        $this->applyPermission($menu);
        $data['mainMenu']           = $menu;
        $data['headerMenuSelected'] = $options['headerMenuSelected'];
    }

    public function checkPermission($moduleCode)
    {
        $permissionGroup = $this->container->get('app_admin.helper.user')->getUserSession()->getPermissionGroup();
        $em              = $this->container->get('doctrine')->getEntityManager();

        $permissions     = $em->getRepository('AppClientBundle:PermissionGroup')->findOneById($permissionGroup);

        // If permissions have not been set up on this user
        if(!is_object($permissions)) return true;

        $permissionCodes = $this->container->get('app_admin.helper.mapping')->getMapping('permission_codes');

        foreach($permissionCodes as $code => $path)
        {
            if($path == $moduleCode && !in_array($code, $permissions->getPermissions())) die('Access denied');
        }

        return true;
    }

    public function applyPermission(&$menu)
    {
        $userSession = $this->container->get('app_admin.helper.user')->getUserSession();
        $adminId     = $userSession->getId();

        $permissionGroup = $this->container->get('app_admin.helper.user')->getUserSession()->getPermissionGroup();
        $em              = $this->container->get('doctrine')->getEntityManager();

        $result     = $em->getRepository('AppClientBundle:PermissionGroup')->findOneById($permissionGroup);

        // If permissions have not been set up on this user
        if(!is_object($result)) return;

        $pages  = array();
        foreach ($result->getPermissions() as $r) {
            $pages[$r] = 1;
        }

        $permissionCodes = $this->container->get('app_admin.helper.mapping')->getMapping('permission_codes');
        foreach ($permissionCodes as $pageId => $pageCode) {
            if (!isset($pages[$pageId])) {
                list($package, $code) = explode('/', $pageCode);
                if (isset($menu[$package]['menu'][$code])) {
                    unset($menu[$package]['menu'][$code]);
                }
            }
        }
        foreach (array('customer', 'supplier', 'automation', 'bank', 'admin') as $package) {
            if (empty($menu[$package]['menu'])) {
                unset($menu[$package]);
            }
        }
    }
}
