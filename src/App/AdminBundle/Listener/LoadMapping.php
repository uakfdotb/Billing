<?php

namespace App\AdminBundle\Listener;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class LoadMapping
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $container = $this->container;
        $mapping = $this->container->get('app_admin.helper.mapping');

        // Common
        $mapping->setMapping('country', 'static', array('function' => '\App\AdminBundle\Business\GlobalUtils::getAllCountries'));
        $mapping->setMapping('gateway_list', 'static', array('function' => '\App\AdminBundle\Business\Common\Constants::getGateways'));

        // Client
        $mapping->setMapping('client_list', 'doctrine', array(
            'repository'    => 'AppUserBundle:User',
            'titleFunction' => '\App\AdminBundle\Business\Client\Utils::getClientName'
        ));
        $mapping->setMapping('client_status', 'static', array('function' => '\App\AdminBundle\Business\Client\Constants::getClientStatus'));

        // Client contact
        $mapping->setMapping('contact_permission', 'static', array('function' => '\App\AdminBundle\Business\ClientContact\Constants::getPermissions'));
        $mapping->setMapping('contact_permission_code', 'static', array('function' => '\App\AdminBundle\Business\ClientContact\Constants::getPermissionCodes'));

        // Project
        $mapping->setMapping('project_status', 'static', array('function' => '\App\AdminBundle\Business\Project\Constants::getProjectStatus'));
        $mapping->setMapping('project_type', 'static', array('function' => '\App\AdminBundle\Business\Project\Constants::getProjectTypes'));

        $mapping->setMapping('staff_list', 'doctrine', array(
            'repository'    => 'AppClientBundle:Admin',
            'titleFunction' => '\App\AdminBundle\Business\Project\Utils::getStaffName'
        ));

        $mapping->setMapping('task_unit', 'static', array('function' => '\App\AdminBundle\Business\ProjectTask\Constants::getUnits'));
        $mapping->setMapping('task_status', 'static', array('function' => '\App\AdminBundle\Business\ProjectTask\Constants::getStatus'));

        // Recurring
        $mapping->setMapping('recurring_schedule', 'static', array('function' => '\App\AdminBundle\Business\Recurring\Constants::getSchedules'));

        // Ticket
        $mapping->setMapping('ticket_status', 'static', array('function' => '\App\ClientBundle\Entity\TicketMessage::$statuses'));
        $mapping->setMapping('ticket_priority', 'static', array('function' => '\App\ClientBundle\Entity\TicketMessage::$priorities'));

        // Config
        $mapping->setMapping('config_date_format', 'static', array('function' => '\App\AdminBundle\Business\Config\Constants::getDateFormat'));
        $mapping->setMapping('config_kendo_date_picker_format', 'static', array('function' => '\App\AdminBundle\Business\Config\Constants::getKendoDateFormat'));
        $mapping->setMapping('config_symfony_date_picker_format', 'static', array('function' => '\App\AdminBundle\Business\Config\Constants::getSymfonyDateFormat'));
        $mapping->setMapping('config_bit_value', 'static', array('function' => '\App\AdminBundle\Business\Config\Constants::getBitValue'));

        // Automation group
        $mapping->setMapping('automation_group_field', 'static', array('function' => '\App\AdminBundle\Business\AutomationGroup\Constants::getAutomationFields'));
        $mapping->setMapping('automation_group_list', 'doctrine', array('repository' => 'AppClientBundle:AutomationGroup'));

        // Supplier
        $mapping->setMapping('supplier_list', 'doctrine', array(
            'repository'    => 'AppClientBundle:Supplier',
            'titleFunction' => '\App\AdminBundle\Business\Supplier\Utils::getSupplierName'
        ));
        $mapping->setMapping('supplier_status', 'static', array('function' => '\App\AdminBundle\Business\Supplier\Constants::getSupplierStatus'));

        // Account
        $mapping->setMapping('account_type', 'static', array('function' => '\App\AdminBundle\Business\Account\Constants::getAccountTypes'));
        $mapping->setMapping('account_direction', 'static', array('function' => '\App\AdminBundle\Business\Account\Constants::getAccountDirections'));
        $mapping->setMapping('account_list', 'doctrine', array('repository' => 'AppClientBundle:Account'));

        // LOG
        $mapping->setMapping('log_type', 'static', array('function' => '\App\AdminBundle\Business\Log\Constants::getLogTypes'));

        // CLIENT LOG
        $mapping->setMapping('client_log_type', 'static', array('function' => '\App\AdminBundle\Business\ClientLog\Constants::getLogTypes'));

        // Estimate
        $mapping->setMapping('estimate_list', 'doctrine', array(
            'repository'    => 'AppClientBundle:ClientEstimate',
            'titleFunction' => '\App\AdminBundle\Business\Estimate\Utils::getEstimateName'
        ));
        $mapping->setMapping('estimate_status', 'static', array('function' => '\App\AdminBundle\Business\Estimate\Constants::getEstimateStatus'));

        // Invoice
        $mapping->setMapping('invoice_status', 'static', array('function' => '\App\ClientBundle\Entity\ClientInvoice::validStatuses'));
        $mapping->setMapping('invoice_list', 'doctrine', array('repository' => 'AppClientBundle:ClientInvoice', 'value' => 'number'));
        $mapping->setMapping('payment_status', 'static', array('function' => '\App\ClientBundle\Entity\ClientPayment::validStatuses'));

        // Staff
        //$mapping->setMapping('staff_permission', 'static', array('function' => '\App\AdminBundle\Business\Staff\Constants::getPermissions'));
        //$mapping->setMapping('staff_permission_code', 'static', array('function' => '\App\AdminBundle\Business\Staff\Constants::getPermissionCodes'));

        // Product
        $mapping->setMapping('product_list', 'doctrine', array('repository' => 'AppClientBundle:Product'));
        $mapping->setMapping('product_group_list', 'doctrine', array('repository' => 'AppClientBundle:ProductGroup'));
        $mapping->setMapping('email_list', 'doctrine', array('repository' => 'AppClientBundle:Email'));
        $mapping->setMapping('product_payment_type', 'static', array('function' => '\App\AdminBundle\Business\Product\Constants::getPaymentTypes'));
        $mapping->setMapping('product_type_list', 'static', array('function' => '\App\AdminBundle\Business\Product\Constants::getProductTypes'));

        // Order
        $mapping->setMapping('order_status', 'static', array('function' => '\App\AdminBundle\Business\Order\Constants::getOrderStatus'));
        $mapping->setMapping('order_payment_term', 'static', array('function' => '\App\AdminBundle\Business\Order\Constants::getOrderPaymentTerms'));
        $mapping->setMapping('order_create_account', 'static', array('function' => '\App\AdminBundle\Business\Order\Constants::getCreateAccountWhen'));

        // Tax
        $mapping->setMapping('tax_list', 'doctrine', array(
            'repository'    => 'AppClientBundle:Tax'
        ));

        // Permission Groups
        $mapping->setMapping('permission_group_list', 'doctrine', array(
            'repository'    => 'AppClientBundle:PermissionGroup'
        ));
        $mapping->setMapping('permission_codes', 'static', array('function' => '\App\AdminBundle\Business\PermissionGroup\Constants::getPermissionCodes'));

        // Servers
        $mapping->setMapping('server_list', 'doctrine', array('repository'    => 'AppClientBundle:Server'));

        // Servers Groups
        $mapping->setMapping('server_group_types', 'static', array('function' => '\App\AdminBundle\Business\ServerGroup\Constants::getServerGroupTypes'));
        $mapping->setMapping('server_group_logic', 'static', array('function' => '\App\AdminBundle\Business\ServerGroup\Constants::getServerGroupLogic'));
        $mapping->setMapping('server_group_list', 'doctrine', array('repository'    => 'AppClientBundle:ServerGroup'));
    }
}
