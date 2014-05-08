<?php
namespace App\AdminBundle\Business\Config;

use App\AdminBundle\Business\Base\BaseEditHandler;
use App\ClientBundle\Entity;

class EditHandler extends BaseEditHandler
{
    public function loadEntity()
    {
        $config = $this->container->get('doctrine')->getRepository('AppClientBundle:Config')->findAll();
        foreach ($config as $c) {
            return $c;
        }
    }

    public function getModelFromEntity($entity)
    {
        return $this->helperCommon->copyEntityToArray($entity);
    }

    public function buildForm($builder)
    {
        // General
        $builder->add('businessName', 'text', array(
            'label'    => 'Business Name',
            'attr'     => array(
                'description' => "'The business name that you'd like to appear on all client correspondence. It is recommended to use your full legal name, such as 'Acme Supplies Ltd' or 'Joe Bloggs T/a Acme Supplies'."
            ),
            'required' => false
        ));
        $builder->add('logo', 'file', array(
            'data_class' => null,
            'label'      => 'Logo',
            'attr'       => array(
                'description' => 'If you would like your business logo to be displayed on invoices and estimates that you send to your customers, please upload one here. A size of approximately 275x75px works well.'
            ),
            'required'   => false
        ));
        $builder->add('defaultEmail', 'text', array(
            'label'    => 'Default Email Address',
            'attr'     => array(
                'description' => "The email address that all correspondence will be sent from, and that will appear in invoices and estimates. Using a long address may result in text wrapping."
            ),
            'required' => false
        ));
        $builder->add('webSite', 'text', array(
            'label'    => 'Website',
            'attr'     => array(
                'description' => "If you would like a web address to be visible on your estimates and invoices, please set it here. Using a long address may result in text wrapping."
            ),
            'required' => false
        ));
        $builder->add('isEnabledDropIn', 'checkbox', array(
            'label'    => 'Drop-in Access',
            'attr'     => array(
                'description' => "If you experience an issue with our software, we may request that you enable this option. This will allow our staff to access your account without your password."
            ),
            'required' => false
        ));

        // Locale
        $builder->add('dateFormat', 'choice', array(
            'label'       => 'Date Format',
            'attr'        => array(
                'description' => 'Choose your date format here. American users may with to use m/d/Y and British users may with to use d/m/Y for example.'
            ),
            'empty_value' => false,
            'choices'     => $this->helperMapping->getMapping('config_date_format'),
            'required'    => false
        ));
        $builder->add('billingCurrency', 'text', array(
            'label'    => 'ISO Currency Code',
            'attr'     => array(
                'description' => 'Enter your ISO currency code here - this is what we will use to send to payment processors. Examples include GBP for Pounds Sterling and USD for US Dollars.'
            ),
            'required' => false
        ));
        $builder->add('currencyCode', 'text', array(
            'label'    => 'Currency Symbol',
            'attr'     => array(
                'description' => 'Enter your currency symbol here - this is what will be displayed both admin and client side next to currency amounts. For example, £ for Pounds Sterling and $ for US dollars.'
            ),
            'required' => false
        ));
        $builder->add('culture', 'text', array(
            'label'    => 'Admin UI Locale',
            'attr'     => array(
                'description' => 'Enter your locale in the form "xx-YY" where xx is your language and YY is your country. For example, those in the UK should enter en-GB and those in the US should enter en-US.'
            ),
            'required' => false
        ));

        // Security
        $builder->add('staffLoginNotify', 'text', array(
            'label'    => 'Staff Login Notify',
            'attr'     => array(
                'description' => "If you'd like to receive an email when a staff member successfully logs in, enter the recipient's email address here. If this is blank, one won't be sent."
            ),
            'required' => false
        ));
        $builder->add('staffLoginFailNotify', 'text', array(
            'label'    => 'Staff Failed Login Notify',
            'attr'     => array(
                'description' => "If you'd like to receive an email when a staff member fails to logs in, enter the recipient's email address here. If this is blank, one won't be sent."
            ),
            'required' => false
        ));
        $builder->add('staffIpVerification', 'choice', array(
            'label'       => 'Staff IP Verification',
            'attr'        => array(
                'description' => "If this is set to enabled, you will be required to log in again should your IP change. In most cases this should be enabled for additional security."
            ),
            'empty_value' => false,
            'choices'     => $this->helperMapping->getMapping('config_bit_value'),
            'required'    => false
        ));
        $builder->add('staffMultipleLogins', 'choice', array(
            'label'       => 'Staff Multiple Logins',
            'attr'        => array(
                'description' => "If this is set to enabled, the same staff member can be logged in from multiple locations at the same time. In most cases, this should be disabled."
            ),
            'empty_value' => false,
            'choices'     => $this->helperMapping->getMapping('config_bit_value'),
            'required'    => false
        ));
        $builder->add('staffTimeout', 'text', array(
            'label'    => 'Staff Inactivity Timeout',
            'attr'     => array(
                'description' => "Staff members will be automatically logged out after this many minutes of inactivity. Set this to zero to disable this functionality."
            ),
            'required' => false
        ));


        // Payment
        $builder->add('businessAddress', 'textarea', array(
            'label'    => 'Business Address',
            'attr'     => array(
                'description' => "The payee address you'd like to appear on your invoices, which will be appended to your business name specified above. In many cases it is a legal requirement that this is true and accurate."
            ),
            'required' => false
        ));
        $builder->add('invoiceNotes', 'textarea', array(
            'label'    => 'Default Invoice Notes',
            'attr'     => array(
                'description' => "Enter your default invoice notes here. This can be changed on a per-invoice basis when you create invoices."
            ),
            'required' => false
        ));
        $builder->add('paymentSuccessEmail', 'text', array(
            'label'    => 'Payment Success Email',
            'attr'     => array(
                'description' => "If you'd like to receive an email when an automatic payment is successfully recorded, please enter the email address you'd like it sent to here. If you leave this blank, one won't be sent."
            ),
            'required' => false
        ));
        $builder->add('paymentFailureEmail', 'text', array(
            'label'    => 'Payment Failure Email',
            'attr'     => array(
                'description' => "If you'd like to receive an email when an anomalous IPN is received, please enter the email address you'd like it sent to here. If you leave this blank, one won't be sent. It is highly recommended that you enable this option."
            ),
            'required' => false
        ));
        $builder->add('isProformaInvoiceEnabled', 'checkbox', array(
                'label'    => 'Proforma Invoice Enable',
                'attr'     => array(
                    'description' => "Enable or Disable Proforma Invoice Prefix"
                ),
                'required' => false
            ));
        $builder->add('proformaInvoicePrefix', 'text', array(
                'label'    => 'Proforma Invoice Prefix',
                'attr'     => array(
                    'description' => "If you would like your proforma invoice numbers to be prefixed, please enter the prefix here. This option requires that proforma invoices are enabled."
                ),
                'required' => false
            ));

        // Default
        $builder->add('idDefaultWorkType', 'choice', array(
            'label'       => 'Default Work Type',
            'attr'        => array(
                'description' => "When time is tracked on a project and that time is billed automatically using the 'bill time' functionality, this work type will be set for the billable items by default. This can be changed on the invoice."
            ),
            'empty_value' => false,
            'choices'     => $this->helperMapping->getMapping('project_type'),
            'required'    => false
        ));
        $builder->add('defaultTax', 'text', array(
            'label'    => 'Default Tax Level',
            'attr'     => array(
                'description' => "When time is tracked on a project and that time is billed automatically using the 'bill time' functionality, this tax level will be set for the invoice. This can be changed on the invoice. Enter this value as a decimal, for example: 50% becomes 0.5."
            ),
            'required' => false
        ));
        $builder->add('estimatePrefix', 'text', array(
            'label'    => 'Estimate Prefix',
            'attr'     => array(
                'description' => "If you would like your estimate numbers to be prefixed, please enter the prefix here."
            ),
            'required' => false
        ));
        $builder->add('invoicePrefix', 'text', array(
            'label'    => 'Invoice Prefix',
            'attr'     => array(
                'description' => "If you would like your invoice numbers to be prefixed, please enter the prefix here."
            ),
            'required' => false
        ));

        // Invoices
        $choicesZeroToThirty = array();
        for($i=0; $i<31; $i++) $choicesZeroToThirty[$i] = $i;
        $choicesOneToThirty = array();
        for($i=1; $i<31; $i++) $choicesOneToThirty[$i] = $i;

        $builder->add('generateInvoice', 'choice', array(
            'label'       => 'Generate Invoice',
            'attr'        => array(
                'description' => 'The number of days before the due date to send recurring invoices'
            ),
            'empty_value' => false,
            'choices'     => $choicesZeroToThirty,
            'required'    => true
        ));
        $builder->add('invoiceEmail', 'textarea', array(
            'label'    => 'New Invoice Email',
            'attr'     => array(
                'description' => "This email will be sent when you click \"Send Invoice\" on the invoices page and automatically when a new recurring invoice is generated. See the help pages for a list of variables that this email supports. A PDF copy of the invoice will be attached automatically."
            ),
            'required' => false
        ));
        $builder->add('sendReminder', 'choice', array(
            'label'       => 'Send Reminder',
            'attr'        => array(
                'description' => 'The number of days before the due date to send an automatic invoice reminder. Note that this is only applicable to invoices where automatic reminders are enabled.'
            ),
            'empty_value' => false,
            'choices'     => $choicesOneToThirty,
            'required'    => false,
            'multiple'    => true
        ));
        $builder->add('reminderEmail', 'textarea', array(
            'label'    => 'Invoice Reminder Email',
            'attr'     => array(
                'description' => "This email will be sent when you click \"Send Reminder\" on the invoices page and automatically the given number of days before the due date.  See the help pages for a list of variables that this email supports. A PDF copy of the invoice will be attached automatically."
            ),
            'required' => false
        ));
        $builder->add('sendOverdue', 'choice', array(
            'label'       => 'Send Overdue Notice',
            'attr'        => array(
                'description' => 'The number of days after the due date to send an automatic invoice overdue notice. Note that this is only applicable to invoices where automatic overdue notices are enabled.'
            ),
            'empty_value' => false,
            'choices'     => $choicesOneToThirty,
            'required'    => false,
            'multiple'    => true
        ));
        $builder->add('overdueEmail', 'textarea', array(
            'label'    => 'Invoice Overdue Email',
            'attr'     => array(
                'description' => "This email will be sent when you click \"Send Overdue Notice\" on the invoices page and automatically the given number of days after the due date.  See the help pages for a list of variables that this email supports. A PDF copy of the invoice will be attached automatically."
            ),
            'required' => false
        ));
        $builder->add('dailySummary', 'checkbox', array(
            'label'    => 'Daily Summary Email',
            'attr'     => array(
                'description' => "If this box is checked, we will email the main account holder every morning with a summary of automatically generated invoices, reminders and overdue notices."
            ),
            'required' => false
        ));

        // Added 29/03/14
        $builder->add('clientHeader', 'textarea', array(
            'label'    => 'Client Area Header',
            'attr'     => array(
                'description' => "Enter HTML code here that will be added to the header of client area, just below the <body> tag. Use this to customise the look of your client area. Javascript is not allowed and will be removed."
            ),
            'required' => false
        ));
        $builder->add('clientFooter', 'textarea', array(
            'label'    => 'Client Area Footer',
            'attr'     => array(
                'description' => "Enter HTML code here that will be added to the footer of the client area, just above the </body> tag. Use this to customise the look of your client area. Javascript is not allowed and will be removed."
            ),
            'required' => false
        ));
        $builder->add('clientMenus', 'choice', array(
            'label'       => 'Client Area Menus',
            'attr'        => array(
                'description' => 'Choose which widgets are available in the client area.'
            ),
            'empty_value' => false,
            'choices'     => array(
                'estimates' => 'estimates',
                'invoices'  => 'invoices',
                'projects'  => 'projects',
                'products'  => 'products',
                'profile'   => 'profile',
                'contacts'  => 'contacts'
            ),
            'required'    => false,
            'multiple'    => true
        ));
        $builder->add('tosUrl', 'text', array(
            'label'    => 'Link To Terms',
            'attr'     => array(
                'description' => "If you would like your customers to have to agree to your terms of service when ordering, enter the URL here."
            ),
            'required' => false
        ));
        $builder->add('privacyUrl', 'text', array(
            'label'    => 'Link To Privacy Policy',
            'attr'     => array(
                'description' => "If you would like your customers to have to agree to your privacy policy when ordering, enter the URL here."
            ),
            'required' => false
        ));
        $builder->add('orderEmail', 'textarea', array(
            'label'    => 'Order Confirmation Email',
            'attr'     => array(
                'description' => "This is the email that will be sent when a new order is placed. Enter the email body here."
            ),
            'required' => false
        ));

        // Added 31/03/14
        $builder->add('maxmindlicensekey', 'text', array(
            'label'    => 'Maxmind Licence Key',
            'attr'     => array(
                'description' => "If you would like to use MaxMind fraud detection, please enter your licence key here."
            ),
            'required' => false
        ));
        $builder->add('maxmindriskscorethreshold', 'text', array(
            'label'    => 'Maxmind Risk Score Threshold',
            'attr'     => array(
                'description' => "If you would like to use MaxMind fraud detection, please enter the risk factor above which orders will be automatically marked as fraudulent."
            ),
            'required' => false
        ));
        $builder->add('maxmindEnabled', 'checkbox', array(
            'label'    => 'Maxmind Enabled',
            'attr'     => array(
                'description' => "Check this box to enable MaxMind automatic fraud detection. You must also specify your own licence key and fraud threshold."
            ),
            'required' => false
        ));


        // Added 01/04/14
        $builder->add('suspendAfter', 'choice', array(
            'label'       => 'Suspend Overdue Products',
            'attr'        => array(
                'description' => 'If you would like products with an associated overdue invoice to be automatically suspended, enter the number of days past due at which the product will be suspended.'
            ),
            'choices'     => $choicesOneToThirty,
            'required'    => false
        ));
        $builder->add('terminateAfter', 'choice', array(
            'label'       => 'Terminate Overdue Products',
            'attr'        => array(
                'description' => 'If you would like products with an associated overdue invoice to be automatically terminated, enter the number of days past due at which the product will be terminated. Ensure that you are using backups before enabling this option.'
            ),
            'choices'     => $choicesOneToThirty,
            'required'    => false
        ));
    }

    public function onSuccess()
    {
        $model   = $this->getForm()->getData();
        $entity  = $this->getEntity();
        $oldLogo = $entity->getLogo();

        $model['clientHeader'] = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $model['clientHeader']);
        $model['clientFooter'] = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $model['clientFooter']);

        foreach ($model as $k => $v) {
            $setMethod = 'set' . ucfirst($k);
            if (method_exists($entity, $setMethod)) {
                $entity->$setMethod($v);
            }
        }

        parent::onSuccess();
    }
}