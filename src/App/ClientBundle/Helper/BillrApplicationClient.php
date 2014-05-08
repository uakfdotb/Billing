<?php

namespace App\ClientBundle\Helper;


class BillrApplicationClient
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

        $data['logoutUrl']     = $router->generate('fos_user_security_logout');
        $data['config']        = $config;


        // New menu

        $menu = array(
            'tickets'  => array('title' => 'TICKETS', 'class' => 'icon-home', 'href' => $router->generate('app_client_ticket_list')),
            'estimates' => array('title' => 'ESTIMATES', 'class' => 'icon-home', 'href' => $router->generate('app_client_estimate_list')),
            'invoices' => array('title' => 'INVOICES', 'class' => 'icon-home', 'href' => $router->generate('app_client_invoice_list')),
            'projects' => array('title' => 'PROJECTS', 'class' => 'icon-home', 'href' => $router->generate('app_client_project_list')),
            'products' => array('title' => 'PRODUCTS',   'class' => 'icon-home',  'href' => $router->generate('app_client_product_list')),
            'profile'  => array('title' => 'PROFILE', 'class' => 'icon-home', 'href' => $router->generate('app_client_profile_edit')),
            'contacts' => array('title' => 'CONTACTS', 'class' => 'icon-home', 'href' => $router->generate('app_client_contact_list')),
            'logout'   => array('title' => 'LOGOUT', 'class' => 'icon-home', 'href' => $router->generate('fos_user_security_logout'))
        );

        /*foreach($menu as $name => $data)
        {
            if(!in_array($name, $config->getClientMenus()) && $name != 'logout') unset($menu[$name]);
        }

        $userSession = $this->container->get('app_admin.helper.user')->getUserSession();

        $permissionCodes = $this->container->get('app_admin.helper.mapping')->getMapping('contact_permission_code');
        foreach ($permissionCodes as $pageId => $code) {
            if ($userSession && !$userSession->hasPermission($pageId)) {
                unset($menu[$code]);
            }
        }*/

        $data['mainMenu'] = $menu;

        $data['headerMenuSelected'] = isset($options['headerMenuSelected']) ? $options['headerMenuSelected'] : null;

    }

    public function checkPermission($idPage)
    {
        /*$userSession = $this->container->get('app_admin.helper.user')->getUserSession();
        if ($userSession && !$userSession->hasPermission($idPage)) {
            die('ACCESS DENIED');
        }*/
    }

}