<?php
namespace App\AdminBundle\Business\Dashboard;

class Constants {
    const WIDGET_SALES_OF_THE_MONTH            = 1;
    const WIDGET_TOTAL_VALUE_OF_SALES          = 2;
    const WIDGET_TOTAL_VALUE_OF_EXPENSES       = 3;
    const WIDGET_PROFIT                        = 4;    
    const WIDGET_BANK_BALANCE                  = 5;
    const WIDGET_CLIENT_STATUS                 = 6;    
    const WIDGET_INVOICE_STATUS                = 7;

    public static function getWidgets() {
        return array(
            self::WIDGET_SALES_OF_THE_MONTH => array(
                'builder'       => 'app_admin.business.dashboard.widget.sales_of_the_month',
                'defaultState'  => 0
            ),
            self::WIDGET_TOTAL_VALUE_OF_SALES => array(
                'builder'       => 'app_admin.business.dashboard.widget.total_value_of_sales',
                'defaultState'  => 0
            ),
            self::WIDGET_TOTAL_VALUE_OF_EXPENSES => array(
                'builder'       => 'app_admin.business.dashboard.widget.total_value_of_expenses',
                'defaultState'  => 0
            ),
            self::WIDGET_PROFIT => array(
                'builder'       => 'app_admin.business.dashboard.widget.profit',
                'defaultState'  => 0
            ),
            self::WIDGET_BANK_BALANCE => array(
                'builder'       => 'app_admin.business.dashboard.widget.bank_balance',
                'defaultState'  => 0
            ),
            self::WIDGET_CLIENT_STATUS => array(
                'builder'       => 'app_admin.business.dashboard.widget.client_status',
                'defaultState'  => 0
            ),
            self::WIDGET_INVOICE_STATUS => array(
                'builder'       => 'app_admin.business.dashboard.widget.invoice_status',
                'defaultState'  => 0
            )
        );
    }
}