<?php

namespace App\ClientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * App\ClientBundle\Entity\Config
 *
 * @ORM\Table(name="config")
 * @ORM\Entity
 */
class Config
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $installDirectory
     *
     * @ORM\Column(name="install_directory", type="string", length=255, nullable=true)
     */
    private $installDirectory;

    /**
     * @var string $adminDirectory
     *
     * @ORM\Column(name="admin_directory", type="string", length=255, nullable=true)
     */
    private $adminDirectory;

    /**
     * @var string $businessName
     *
     * @ORM\Column(name="business_name", type="string", length=255, nullable=true)
     */
    private $businessName;

    /**
     * @var string $defaultEmail
     *
     * @ORM\Column(name="default_email", type="string", length=255, nullable=true)
     */
    private $defaultEmail;

    /**
     * @var string $licenceKey
     *
     * @ORM\Column(name="licence_key", type="string", length=30, nullable=true)
     */
    private $licenceKey;

    /**
     * @var string $clientLanguage
     *
     * @ORM\Column(name="client_language", type="string", length=16, nullable=true)
     */
    private $clientLanguage;

    /**
     * @var string $dateFormat
     *
     * @ORM\Column(name="date_format", type="string", length=5, nullable=true)
     */
    private $dateFormat;

    /**
     * @var string $currencyCode
     *
     * @ORM\Column(name="currency_code", type="string", length=10, nullable=true)
     */
    private $currencyCode;

    /**
     * @var string $billingCurrency
     *
     * @ORM\Column(name="billing_currency", type="string", length=3, nullable=true)
     */
    private $billingCurrency;

    /**
     * @var boolean $staffIpVerification
     *
     * @ORM\Column(name="staff_ip_verification", type="boolean", nullable=true)
     */
    private $staffIpVerification;

    /**
     * @var boolean $staffMultipleLogins
     *
     * @ORM\Column(name="staff_multiple_logins", type="boolean", nullable=true)
     */
    private $staffMultipleLogins;

    /**
     * @var integer $staffTimeout
     *
     * @ORM\Column(name="staff_timeout", type="integer", nullable=true)
     */
    private $staffTimeout;

    /**
     * @var string $staffLoginNotify
     *
     * @ORM\Column(name="staff_login_notify", type="string", length=255, nullable=true)
     */
    private $staffLoginNotify;

    /**
     * @var string $staffLoginFailNotify
     *
     * @ORM\Column(name="staff_login_fail_notify", type="string", length=255, nullable=true)
     */
    private $staffLoginFailNotify;

    /**
     * @var string $recaptchaPublic
     *
     * @ORM\Column(name="recaptcha_public", type="string", length=255, nullable=true)
     */
    private $recaptchaPublic;

    /**
     * @var string $recaptchaPrivate
     *
     * @ORM\Column(name="recaptcha_private", type="string", length=255, nullable=true)
     */
    private $recaptchaPrivate;

    /**
     * @var integer $staffLoginGreylist
     *
     * @ORM\Column(name="staff_login_greylist", type="integer", nullable=true)
     */
    private $staffLoginGreylist;

    /**
     * @var string $businessAddress
     *
     * @ORM\Column(name="business_address", type="text", nullable=true)
     */
    private $businessAddress;

    /**
     * @var string $invoiceNotes
     *
     * @ORM\Column(name="invoice_notes", type="text", nullable=true)
     */
    private $invoiceNotes;

    /**
     * @var string $paymentSuccessEmail
     *
     * @ORM\Column(name="payment_success_email", type="string", length=255, nullable=true)
     */
    private $paymentSuccessEmail;

    /**
     * @var string $paymentFailureEmail
     *
     * @ORM\Column(name="payment_failure_email", type="string", length=255, nullable=true)
     */
    private $paymentFailureEmail;

    /**
     * @var string $localkey
     *
     * @ORM\Column(name="localkey", type="text", nullable=true)
     */
    private $localkey;

    /**
     * @var integer $idDefaultWorkType
     *
     * @ORM\Column(name="id_default_work_type", type="integer", nullable=true)
     */
    private $idDefaultWorkType;

    /**
     * @var float $defaultTax
     *
     * @ORM\Column(name="default_tax", type="decimal", nullable=true)
     */
    private $defaultTax;

    /**
     * @var float $defaultDiscount
     *
     * @ORM\Column(name="default_discount", type="decimal", nullable=true)
     */
    private $defaultDiscount;

    /**
     * @var string $version
     *
     * @ORM\Column(name="version", type="string", length=11, nullable=true)
     */
    private $version;

    /**
     * @var string $culture
     *
     * @ORM\Column(name="culture", type="string", length=6, nullable=true)
     */
    private $culture;

    /**
     * @var string $ticketimaphost
     *
     * @ORM\Column(name="ticketImapHost", type="string", length=255, nullable=true)
     */
    private $ticketimaphost;

    /**
     * @var string $ticketimapusername
     *
     * @ORM\Column(name="ticketImapUsername", type="string", length=255, nullable=true)
     */
    private $ticketimapusername;

    /**
     * @var string $ticketimappassword
     *
     * @ORM\Column(name="ticketImapPassword", type="string", length=255, nullable=true)
     */
    private $ticketimappassword;

    /**
     * @var string $maxmindlicensekey
     *
     * @ORM\Column(name="maxmindLicenseKey", type="string", length=255, nullable=true)
     */
    private $maxmindlicensekey;

    /**
     * @var float $maxmindriskscorethreshold
     *
     * @ORM\Column(name="maxmindRiskScoreThreshold", type="decimal", nullable=true)
     */
    private $maxmindriskscorethreshold;

    /**
     * @var boolean $maxmindEnabled
     *
     * @ORM\Column(name="maxmind_enabled", type="boolean", nullable=true)
     */
    private $maxmindEnabled;

    /**
     * @var string $logo
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @var string $estimatePrefix
     *
     * @ORM\Column(name="estimate_prefix", type="string", length=255, nullable=true)
     */
    private $estimatePrefix;

    /**
     * @var string $invoicePrefix
     *
     * @ORM\Column(name="invoice_prefix", type="string", length=255, nullable=true)
     */
    private $invoicePrefix;

    /**
     * @var string $utilKey
     *
     * @ORM\Column(name="util_key", type="string", length=50, nullable=true)
     */
    private $utilKey;

    /**
     * @var string $cpanelApiUrl
     *
     * @ORM\Column(name="cpanel_api_url", type="string", length=255, nullable=true)
     */
    private $cpanelApiUrl;

    /**
     * @var string $cpanelApiUsername
     *
     * @ORM\Column(name="cpanel_api_username", type="string", length=255, nullable=true)
     */
    private $cpanelApiUsername;

    /**
     * @var string $cpanelApiPassword
     *
     * @ORM\Column(name="cpanel_api_password", type="string", length=255, nullable=true)
     */
    private $cpanelApiPassword;

    /**
     * @var string $solusvmApiUrl
     *
     * @ORM\Column(name="solusvm_api_url", type="string", length=255, nullable=true)
     */
    private $solusvmApiUrl;

    /**
     * @var string $solusvmApiUsername
     *
     * @ORM\Column(name="solusvm_api_username", type="string", length=255, nullable=true)
     */
    private $solusvmApiUsername;

    /**
     * @var string $solusvmApiPassword
     *
     * @ORM\Column(name="solusvm_api_password", type="string", length=255, nullable=true)
     */
    private $solusvmApiPassword;

    /**
     * @var boolean $logglyEnabled
     *
     * @ORM\Column(name="loggly_enabled", type="boolean", nullable=true)
     */
    private $logglyEnabled;

    /**
     * @var string $logglyConsumerKey
     *
     * @ORM\Column(name="loggly_consumer_key", type="string", length=255, nullable=true)
     */
    private $logglyConsumerKey;

    /**
     * @var string $logglyConsumerSecret
     *
     * @ORM\Column(name="loggly_consumer_secret", type="string", length=255, nullable=true)
     */
    private $logglyConsumerSecret;

    /**
     * @var string $logglyApiUrl
     *
     * @ORM\Column(name="loggly_api_url", type="string", length=255, nullable=true)
     */
    private $logglyApiUrl;

    /**
     * @var boolean $isProformaInvoiceEnabled
     *
     * @ORM\Column(name="is_proforma_invoice_enabled", type="boolean", options={"default":false})
     */
    private $isProformaInvoiceEnabled;

    /**
     * @var string $proformaInvoicePrefix
     *
     * @ORM\Column(name="proforma_invoice_prefix", type="string", length=255, nullable=true, options={"default":"PI"})
     */
    private $proformaInvoicePrefix;

    /**
     * @var integer $countProformaInvoice
     *
     * @ORM\Column(name="count_proforma_invoice", type="integer", nullable=true, options={"default":1})
     */
    private $countProformaInvoice;

    /**
     * @var integer $countProformaPaidInvoice
     *
     * @ORM\Column(name="count_proforma_paid_invoice", type="integer", nullable=true, options={"default":1})
     */
    private $countProformaPaidInvoice;

    /**
    * @var boolean $isEnabledDropIn
    *
    * @ORM\Column(name="is_enabled_drop_in", type="boolean", options={"default":false})
    */
    private $isEnabledDropIn;

    /**
     * @var string $webSite
     *
     * @ORM\Column(name="website", type="string", options={"default":false})
     */
    private $webSite;

    /**
     * @ORM\Column(name="current_invoices", type="integer", nullable=true)
     */
    private $currentInvoices = 0;

    /**
     * @ORM\Column(name="generate_invoice", type="integer", nullable=true)
     */
    private $generateInvoice;

    /**
     * @ORM\Column(name="invoice_email", type="text", nullable=true)
     */
    private $invoiceEmail;

    /**
     * @ORM\Column(name="send_reminder", type="array", nullable=true)
     */
    private $sendReminder;

    /**
     * @ORM\Column(name="reminder_email", type="text", nullable=true)
     */
    private $reminderEmail;

    /**
     * @ORM\Column(name="send_overdue", type="array", nullable=true)
     */
    private $sendOverdue;

    /**
     * @var boolean $dailySummary
     *
     * @ORM\Column(name="daily_summary", type="boolean", nullable=true)
     */
    private $dailySummary;

    /**
     * @var string $tosUrl
     *
     * @ORM\Column(name="tos_url", type="string", length=255, nullable=true)
     */
    private $tosUrl;

    /**
     * @var string $privacyUrl
     *
     * @ORM\Column(name="privacy_url", type="string", length=255, nullable=true)
     */
    private $privacyUrl;

    /**
     * @var string $defaultPhone
     *
     * @ORM\Column(name="default_phone", type="string", length=255, nullable=true)
     */
    private $defaultPhone;

    /**
     * @ORM\Column(name="client_header", type="text", nullable=true)
     */
    private $clientHeader;

    /**
     * @ORM\Column(name="client_footer", type="text", nullable=true)
     */
    private $clientFooter;

    /**
     * @ORM\Column(name="client_menus", type="array", nullable=true)
     */
    private $clientMenus;

    /**
     * @ORM\Column(name="order_email", type="text", nullable=true)
     */
    private $orderEmail;

    /**
     * @ORM\Column(name="suspend_after", type="integer", nullable=true)
     */
    private $suspendAfter;

    /**
     * @ORM\Column(name="terminate_after", type="integer", nullable=true)
     */
    private $terminateAfter;

    /**
     * @param mixed $generateInvoice
     */
    public function setGenerateInvoice($generateInvoice)
    {
        $this->generateInvoice = $generateInvoice;
    }

    /**
     * @return mixed
     */
    public function getGenerateInvoice()
    {
        return $this->generateInvoice;
    }

    /**
     * @param mixed $invoiceEmail
     */
    public function setInvoiceEmail($invoiceEmail)
    {
        $this->invoiceEmail = $invoiceEmail;
    }

    /**
     * @return mixed
     */
    public function getInvoiceEmail()
    {
        return $this->invoiceEmail;
    }

    /**
     * @param mixed $overdueEmail
     */
    public function setOverdueEmail($overdueEmail)
    {
        $this->overdueEmail = $overdueEmail;
    }

    /**
     * @return mixed
     */
    public function getOverdueEmail()
    {
        return $this->overdueEmail;
    }

    /**
     * @param mixed $reminderEmail
     */
    public function setReminderEmail($reminderEmail)
    {
        $this->reminderEmail = $reminderEmail;
    }

    /**
     * @return mixed
     */
    public function getReminderEmail()
    {
        return $this->reminderEmail;
    }

    /**
     * @param mixed $sendOverdue
     */
    public function setSendOverdue($sendOverdue)
    {
        $this->sendOverdue = $sendOverdue;
    }

    /**
     * @return mixed
     */
    public function getSendOverdue()
    {
        return $this->sendOverdue;
    }

    /**
     * @param mixed $sendReminder
     */
    public function setSendReminder($sendReminder)
    {
        $this->sendReminder = $sendReminder;
    }

    /**
     * @return mixed
     */
    public function getSendReminder()
    {
        return $this->sendReminder;
    }

    /**
     * @ORM\Column(name="overdue_email", type="text", nullable=true)
     */
    private $overdueEmail;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set installDirectory
     *
     * @param string $installDirectory
     * @return Config
     */
    public function setInstallDirectory($installDirectory)
    {
        $this->installDirectory = $installDirectory;

        return $this;
    }

    /**
     * Get installDirectory
     *
     * @return string
     */
    public function getInstallDirectory()
    {
        return $this->installDirectory;
    }

    /**
     * Set adminDirectory
     *
     * @param string $adminDirectory
     * @return Config
     */
    public function setAdminDirectory($adminDirectory)
    {
        $this->adminDirectory = $adminDirectory;

        return $this;
    }

    /**
     * Get adminDirectory
     *
     * @return string
     */
    public function getAdminDirectory()
    {
        return $this->adminDirectory;
    }

    /**
     * Set businessName
     *
     * @param string $businessName
     * @return Config
     */
    public function setBusinessName($businessName)
    {
        $this->businessName = $businessName;

        return $this;
    }

    /**
     * Get businessName
     *
     * @return string
     */
    public function getBusinessName()
    {
        return $this->businessName;
    }

    /**
     * Set defaultEmail
     *
     * @param string $defaultEmail
     * @return Config
     */
    public function setDefaultEmail($defaultEmail)
    {
        $this->defaultEmail = $defaultEmail;

        return $this;
    }

    /**
     * Get defaultEmail
     *
     * @return string
     */
    public function getDefaultEmail()
    {
        return $this->defaultEmail;
    }

    /**
     * Set licenceKey
     *
     * @param string $licenceKey
     * @return Config
     */
    public function setLicenceKey($licenceKey)
    {
        $this->licenceKey = $licenceKey;

        return $this;
    }

    /**
     * Get licenceKey
     *
     * @return string
     */
    public function getLicenceKey()
    {
        return $this->licenceKey;
    }

    /**
     * Set clientLanguage
     *
     * @param string $clientLanguage
     * @return Config
     */
    public function setClientLanguage($clientLanguage)
    {
        $this->clientLanguage = $clientLanguage;

        return $this;
    }

    /**
     * Get clientLanguage
     *
     * @return string
     */
    public function getClientLanguage()
    {
        return $this->clientLanguage;
    }

    /**
     * Set dateFormat
     *
     * @param string $dateFormat
     * @return Config
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;

        return $this;
    }

    /**
     * Get dateFormat
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Set currencyCode
     *
     * @param string $currencyCode
     * @return Config
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    /**
     * Get currencyCode
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Set billingCurrency
     *
     * @param string $billingCurrency
     * @return Config
     */
    public function setBillingCurrency($billingCurrency)
    {
        $this->billingCurrency = $billingCurrency;

        return $this;
    }

    /**
     * Get billingCurrency
     *
     * @return string
     */
    public function getBillingCurrency()
    {
        return $this->billingCurrency;
    }

    /**
     * Set staffIpVerification
     *
     * @param boolean $staffIpVerification
     * @return Config
     */
    public function setStaffIpVerification($staffIpVerification)
    {
        $this->staffIpVerification = $staffIpVerification;

        return $this;
    }

    /**
     * Get staffIpVerification
     *
     * @return boolean
     */
    public function getStaffIpVerification()
    {
        return $this->staffIpVerification;
    }

    /**
     * Set staffMultipleLogins
     *
     * @param boolean $staffMultipleLogins
     * @return Config
     */
    public function setStaffMultipleLogins($staffMultipleLogins)
    {
        $this->staffMultipleLogins = $staffMultipleLogins;

        return $this;
    }

    /**
     * Get staffMultipleLogins
     *
     * @return boolean
     */
    public function getStaffMultipleLogins()
    {
        return $this->staffMultipleLogins;
    }

    /**
     * Set staffTimeout
     *
     * @param integer $staffTimeout
     * @return Config
     */
    public function setStaffTimeout($staffTimeout)
    {
        $this->staffTimeout = $staffTimeout;

        return $this;
    }

    /**
     * Get staffTimeout
     *
     * @return integer
     */
    public function getStaffTimeout()
    {
        return $this->staffTimeout;
    }

    /**
     * Set countProformaPaidInvoice
     *
     * @param integer $countProformaPaidInvoice
     * @return Config
     */
    public function setCountProformaPaidInvoice($countProformaPaidInvoice)
    {
        $this->countProformaPaidInvoice = $countProformaPaidInvoice;

        return $this;
    }

    /**
     * Get countProformaPaidInvoice
     *
     * @return integer
     */
    public function getCountProformaPaidInvoice()
    {
        return $this->countProformaPaidInvoice;
    }

    /**
     * Set staffLoginNotify
     *
     * @param string $staffLoginNotify
     * @return Config
     */
    public function setStaffLoginNotify($staffLoginNotify)
    {
        $this->staffLoginNotify = $staffLoginNotify;

        return $this;
    }

    /**
     * Get staffLoginNotify
     *
     * @return string
     */
    public function getStaffLoginNotify()
    {
        return $this->staffLoginNotify;
    }

    /**
     * Set staffLoginFailNotify
     *
     * @param string $staffLoginFailNotify
     * @return Config
     */
    public function setStaffLoginFailNotify($staffLoginFailNotify)
    {
        $this->staffLoginFailNotify = $staffLoginFailNotify;

        return $this;
    }

    /**
     * Get staffLoginFailNotify
     *
     * @return string
     */
    public function getStaffLoginFailNotify()
    {
        return $this->staffLoginFailNotify;
    }

    /**
     * Set recaptchaPublic
     *
     * @param string $recaptchaPublic
     * @return Config
     */
    public function setRecaptchaPublic($recaptchaPublic)
    {
        $this->recaptchaPublic = $recaptchaPublic;

        return $this;
    }

    /**
     * Get recaptchaPublic
     *
     * @return string
     */
    public function getRecaptchaPublic()
    {
        return $this->recaptchaPublic;
    }

    /**
     * Set recaptchaPrivate
     *
     * @param string $recaptchaPrivate
     * @return Config
     */
    public function setRecaptchaPrivate($recaptchaPrivate)
    {
        $this->recaptchaPrivate = $recaptchaPrivate;

        return $this;
    }

    /**
     * Get recaptchaPrivate
     *
     * @return string
     */
    public function getRecaptchaPrivate()
    {
        return $this->recaptchaPrivate;
    }

    /**
     * Set staffLoginGreylist
     *
     * @param integer $staffLoginGreylist
     * @return Config
     */
    public function setStaffLoginGreylist($staffLoginGreylist)
    {
        $this->staffLoginGreylist = $staffLoginGreylist;

        return $this;
    }

    /**
     * Get staffLoginGreylist
     *
     * @return integer
     */
    public function getStaffLoginGreylist()
    {
        return $this->staffLoginGreylist;
    }

    /**
     * Set businessAddress
     *
     * @param string $businessAddress
     * @return Config
     */
    public function setBusinessAddress($businessAddress)
    {
        $this->businessAddress = $businessAddress;

        return $this;
    }

    /**
     * Get businessAddress
     *
     * @return string
     */
    public function getBusinessAddress()
    {
        return $this->businessAddress;
    }

    /**
     * Set invoiceNotes
     *
     * @param string $invoiceNotes
     * @return Config
     */
    public function setInvoiceNotes($invoiceNotes)
    {
        $this->invoiceNotes = $invoiceNotes;

        return $this;
    }

    /**
     * Get invoiceNotes
     *
     * @return string
     */
    public function getInvoiceNotes()
    {
        return $this->invoiceNotes;
    }

    /**
     * Set paymentSuccessEmail
     *
     * @param string $paymentSuccessEmail
     * @return Config
     */
    public function setPaymentSuccessEmail($paymentSuccessEmail)
    {
        $this->paymentSuccessEmail = $paymentSuccessEmail;

        return $this;
    }

    /**
     * Get paymentSuccessEmail
     *
     * @return string
     */
    public function getPaymentSuccessEmail()
    {
        return $this->paymentSuccessEmail;
    }

    /**
     * Set paymentFailureEmail
     *
     * @param string $paymentFailureEmail
     * @return Config
     */
    public function setPaymentFailureEmail($paymentFailureEmail)
    {
        $this->paymentFailureEmail = $paymentFailureEmail;

        return $this;
    }

    /**
     * Get paymentFailureEmail
     *
     * @return string
     */
    public function getPaymentFailureEmail()
    {
        return $this->paymentFailureEmail;
    }

    /**
     * Set localkey
     *
     * @param string $localkey
     * @return Config
     */
    public function setLocalkey($localkey)
    {
        $this->localkey = $localkey;

        return $this;
    }

    /**
     * Get localkey
     *
     * @return string
     */
    public function getLocalkey()
    {
        return $this->localkey;
    }

    /**
     * Set idDefaultWorkType
     *
     * @param integer $idDefaultWorkType
     * @return Config
     */
    public function setIdDefaultWorkType($idDefaultWorkType)
    {
        $this->idDefaultWorkType = $idDefaultWorkType;

        return $this;
    }

    /**
     * Get idDefaultWorkType
     *
     * @return integer
     */
    public function getIdDefaultWorkType()
    {
        return $this->idDefaultWorkType;
    }

    /**
     * Set defaultTax
     *
     * @param float $defaultTax
     * @return Config
     */
    public function setDefaultTax($defaultTax)
    {
        $this->defaultTax = $defaultTax;

        return $this;
    }

    /**
     * Get defaultTax
     *
     * @return float
     */
    public function getDefaultTax()
    {
        return $this->defaultTax;
    }

    /**
     * Set defaultDiscount
     *
     * @param float $defaultDiscount
     * @return Config
     */
    public function setDefaultDiscount($defaultDiscount)
    {
        $this->defaultDiscount = $defaultDiscount;

        return $this;
    }

    /**
     * Get defaultDiscount
     *
     * @return float
     */
    public function getDefaultDiscount()
    {
        return $this->defaultDiscount;
    }

    /**
     * Set version
     *
     * @param string $version
     * @return Config
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * Get version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Set culture
     *
     * @param string $culture
     * @return Config
     */
    public function setCulture($culture)
    {
        $this->culture = $culture;

        return $this;
    }

    /**
     * Get culture
     *
     * @return string
     */
    public function getCulture()
    {
        return $this->culture;
    }

    /**
     * Set ticketimaphost
     *
     * @param string $ticketimaphost
     * @return Config
     */
    public function setTicketimaphost($ticketimaphost)
    {
        $this->ticketimaphost = $ticketimaphost;

        return $this;
    }

    /**
     * Get ticketimaphost
     *
     * @return string
     */
    public function getTicketimaphost()
    {
        return $this->ticketimaphost;
    }

    /**
     * Set ticketimapusername
     *
     * @param string $ticketimapusername
     * @return Config
     */
    public function setTicketimapusername($ticketimapusername)
    {
        $this->ticketimapusername = $ticketimapusername;

        return $this;
    }

    /**
     * Get ticketimapusername
     *
     * @return string
     */
    public function getTicketimapusername()
    {
        return $this->ticketimapusername;
    }

    /**
     * Set ticketimappassword
     *
     * @param string $ticketimappassword
     * @return Config
     */
    public function setTicketimappassword($ticketimappassword)
    {
        $this->ticketimappassword = $ticketimappassword;

        return $this;
    }

    /**
     * Get ticketimappassword
     *
     * @return string
     */
    public function getTicketimappassword()
    {
        return $this->ticketimappassword;
    }

    /**
     * Set maxmindlicensekey
     *
     * @param string $maxmindlicensekey
     * @return Config
     */
    public function setMaxmindlicensekey($maxmindlicensekey)
    {
        $this->maxmindlicensekey = $maxmindlicensekey;

        return $this;
    }

    /**
     * Get maxmindlicensekey
     *
     * @return string
     */
    public function getMaxmindlicensekey()
    {
        return $this->maxmindlicensekey;
    }

    /**
     * Set maxmindriskscorethreshold
     *
     * @param float $maxmindriskscorethreshold
     * @return Config
     */
    public function setMaxmindriskscorethreshold($maxmindriskscorethreshold)
    {
        $this->maxmindriskscorethreshold = $maxmindriskscorethreshold;

        return $this;
    }

    /**
     * Get maxmindriskscorethreshold
     *
     * @return float
     */
    public function getMaxmindriskscorethreshold()
    {
        return $this->maxmindriskscorethreshold;
    }

    /**
     * Set maxmindEnabled
     *
     * @param boolean $maxmindEnabled
     * @return Config
     */
    public function setMaxmindEnabled($maxmindEnabled)
    {
        $this->maxmindEnabled = $maxmindEnabled;

        return $this;
    }

    /**
     * Get maxmindEnabled
     *
     * @return boolean
     */
    public function getMaxmindEnabled()
    {
        return $this->maxmindEnabled;
    }

    /**
     * Set logo
     *
     * @param string $logo
     * @return Config
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set estimatePrefix
     *
     * @param string $estimatePrefix
     * @return Config
     */
    public function setEstimatePrefix($estimatePrefix)
    {
        $this->estimatePrefix = $estimatePrefix;

        return $this;
    }

    /**
     * Get estimatePrefix
     *
     * @return string
     */
    public function getEstimatePrefix()
    {
        return $this->estimatePrefix;
    }

    /**
     * Set invoicePrefix
     *
     * @param string $invoicePrefix
     * @return Config
     */
    public function setInvoicePrefix($invoicePrefix)
    {
        $this->invoicePrefix = $invoicePrefix;

        return $this;
    }

    /**
     * Get invoicePrefix
     *
     * @return string
     */
    public function getInvoicePrefix()
    {
        return $this->invoicePrefix;
    }

    /**
     * Set utilKey
     *
     * @param string $utilKey
     * @return Config
     */
    public function setUtilKey($utilKey)
    {
        $this->utilKey = $utilKey;

        return $this;
    }

    /**
     * Get utilKey
     *
     * @return string
     */
    public function getUtilKey()
    {
        return $this->utilKey;
    }

    /**
     * Set cpanelApiUrl
     *
     * @param string $cpanelApiUrl
     * @return Config
     */
    public function setCpanelApiUrl($cpanelApiUrl)
    {
        $this->cpanelApiUrl = $cpanelApiUrl;

        return $this;
    }

    /**
     * Get cpanelApiUrl
     *
     * @return string
     */
    public function getCpanelApiUrl()
    {
        return $this->cpanelApiUrl;
    }

    /**
     * Set cpanelApiUsername
     *
     * @param string $cpanelApiUsername
     * @return Config
     */
    public function setCpanelApiUsername($cpanelApiUsername)
    {
        $this->cpanelApiUsername = $cpanelApiUsername;

        return $this;
    }

    /**
     * Get cpanelApiUsername
     *
     * @return string
     */
    public function getCpanelApiUsername()
    {
        return $this->cpanelApiUsername;
    }

    /**
     * Set cpanelApiPassword
     *
     * @param string $cpanelApiPassword
     * @return Config
     */
    public function setCpanelApiPassword($cpanelApiPassword)
    {
        $this->cpanelApiPassword = $cpanelApiPassword;

        return $this;
    }

    /**
     * Get cpanelApiPassword
     *
     * @return string
     */
    public function getCpanelApiPassword()
    {
        return $this->cpanelApiPassword;
    }

    /**
     * Set solusvmApiUrl
     *
     * @param string $solusvmApiUrl
     * @return Config
     */
    public function setSolusvmApiUrl($solusvmApiUrl)
    {
        $this->solusvmApiUrl = $solusvmApiUrl;

        return $this;
    }

    /**
     * Get solusvmApiUrl
     *
     * @return string
     */
    public function getSolusvmApiUrl()
    {
        return $this->solusvmApiUrl;
    }

    /**
     * Set solusvmApiUsername
     *
     * @param string $solusvmApiUsername
     * @return Config
     */
    public function setSolusvmApiUsername($solusvmApiUsername)
    {
        $this->solusvmApiUsername = $solusvmApiUsername;

        return $this;
    }

    /**
     * Get solusvmApiUsername
     *
     * @return string
     */
    public function getSolusvmApiUsername()
    {
        return $this->solusvmApiUsername;
    }

    /**
     * Set solusvmApiPassword
     *
     * @param string $solusvmApiPassword
     * @return Config
     */
    public function setSolusvmApiPassword($solusvmApiPassword)
    {
        $this->solusvmApiPassword = $solusvmApiPassword;

        return $this;
    }

    /**
     * Get solusvmApiPassword
     *
     * @return string
     */
    public function getSolusvmApiPassword()
    {
        return $this->solusvmApiPassword;
    }

    /**
     * Set logglyEnabled
     *
     * @param boolean $logglyEnabled
     * @return Config
     */
    public function setLogglyEnabled($logglyEnabled)
    {
        $this->logglyEnabled = $logglyEnabled;

        return $this;
    }

    /**
     * Get logglyEnabled
     *
     * @return boolean
     */
    public function getLogglyEnabled()
    {
        return $this->logglyEnabled;
    }

    /**
     * Set logglyConsumerKey
     *
     * @param string $logglyConsumerKey
     * @return Config
     */
    public function setLogglyConsumerKey($logglyConsumerKey)
    {
        $this->logglyConsumerKey = $logglyConsumerKey;

        return $this;
    }

    /**
     * Get logglyConsumerKey
     *
     * @return string
     */
    public function getLogglyConsumerKey()
    {
        return $this->logglyConsumerKey;
    }

    /**
     * Set logglyConsumerSecret
     *
     * @param string $logglyConsumerSecret
     * @return Config
     */
    public function setLogglyConsumerSecret($logglyConsumerSecret)
    {
        $this->logglyConsumerSecret = $logglyConsumerSecret;

        return $this;
    }

    /**
     * Get logglyConsumerSecret
     *
     * @return string
     */
    public function getLogglyConsumerSecret()
    {
        return $this->logglyConsumerSecret;
    }

    /**
     * Set logglyApiUrl
     *
     * @param string $logglyApiUrl
     * @return Config
     */
    public function setLogglyApiUrl($logglyApiUrl)
    {
        $this->logglyApiUrl = $logglyApiUrl;

        return $this;
    }

    /**
     * Get logglyApiUrl
     *
     * @return string
     */
    public function getLogglyApiUrl()
    {
        return $this->logglyApiUrl;
    }

    /**
     * @param int $countProformaInvoice
     */
    public function setCountProformaInvoice($countProformaInvoice)
    {
        $this->countProformaInvoice = $countProformaInvoice;
    }

    /**
     * @return int
     */
    public function getCountProformaInvoice()
    {
        return $this->countProformaInvoice;
    }

    /**
     * @param boolean $isProformaInvoiceEnabled
     */
    public function setIsProformaInvoiceEnabled($isProformaInvoiceEnabled)
    {
        $this->isProformaInvoiceEnabled = $isProformaInvoiceEnabled;
    }

    /**
     * @return boolean
     */
    public function getIsProformaInvoiceEnabled()
    {
        return $this->isProformaInvoiceEnabled;
    }

    /**
     * @param string $proformaInvoicePrefix
     */
    public function setProformaInvoicePrefix($proformaInvoicePrefix)
    {
        $this->proformaInvoicePrefix = $proformaInvoicePrefix;
    }

    /**
     * @return string
     */
    public function getProformaInvoicePrefix()
    {
        return $this->proformaInvoicePrefix;
    }

    /**
     * @param boolean $isEnabledDropIn
     */
    public function setIsEnabledDropIn($isEnabledDropIn)
    {
        $this->isEnabledDropIn = $isEnabledDropIn;
    }

    /**
     * @return boolean
     */
    public function getIsEnabledDropIn()
    {
        return $this->isEnabledDropIn;
    }

    /**
     * @param int $number
     */
    public function incrementCountProformaInvoice($number = 1)
    {
        $this->setCountProformaInvoice($this->getCountProformaInvoice() + $number);
    }

    /**
     * @param int $number
     */
    public function incrementCountProformaPaidInvoice($number = 1)
    {
        $this->setCountProformaPaidInvoice($this->getCountProformaPaidInvoice() + $number);
    }

    /**
     * @param int $number
     */
    public function incrementCurrentInvoices($number = 1)
    {
        $this->setCurrentInvoices($this->getCurrentInvoices() + $number);
    }

    /**
     * @param string $webSite
     */
    public function setWebSite($webSite)
    {
        $this->webSite = $webSite;
    }

    /**
     * @return string
     */
    public function getWebSite()
    {
        return $this->webSite;
    }


    /**
     * @param mixed $currentInvoices
     * @return $this
     */
    public function setCurrentInvoices($currentInvoices)
    {
        $this->currentInvoices = $currentInvoices;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrentInvoices()
    {
        return $this->currentInvoices;
    }

    /**
     * @param boolean $dailySummary
     */
    public function setDailySummary($dailySummary)
    {
        $this->dailySummary = $dailySummary;
    }

    /**
     * @return boolean
     */
    public function getDailySummary()
    {
        return $this->dailySummary;
    }

    /**
     * Set tosUrl
     *
     * @param string $tosUrl
     * @return Config
     */
    public function setTosUrl($tosUrl)
    {
        $this->tosUrl = $tosUrl;

        return $this;
    }

    /**
     * Get tosUrl
     *
     * @return string
     */
    public function getTosUrl()
    {
        return $this->tosUrl;
    }

    /**
     * Set privacyUrl
     *
     * @param string $privacyUrl
     * @return Config
     */
    public function setPrivacyUrl($privacyUrl)
    {
        $this->privacyUrl = $privacyUrl;

        return $this;
    }

    /**
     * Get privacyUrl
     *
     * @return string
     */
    public function getPrivacyUrl()
    {
        return $this->privacyUrl;
    }

    /**
     * Set defaultPhone
     *
     * @param string $defaultPhone
     * @return Config
     */
    public function setDefaultPhone($defaultPhone)
    {
        $this->defaultPhone = $defaultPhone;

        return $this;
    }

    /**
     * Get defaultPhone
     *
     * @return string
     */
    public function getDefaultPhone()
    {
        return $this->defaultPhone;
    }

    /**
     * @param mixed $clientFooter
     */
    public function setClientFooter($clientFooter)
    {
        $this->clientFooter = $clientFooter;
    }

    /**
     * @return mixed
     */
    public function getClientFooter()
    {
        return $this->clientFooter;
    }

    /**
     * @param mixed $clientHeader
     */
    public function setClientHeader($clientHeader)
    {
        $this->clientHeader = $clientHeader;
    }

    /**
     * @return mixed
     */
    public function getClientHeader()
    {
        return $this->clientHeader;
    }

    /**
     * @param mixed $clientMenus
     */
    public function setClientMenus($clientMenus)
    {
        $this->clientMenus = $clientMenus;
    }

    /**
     * @return mixed
     */
    public function getClientMenus()
    {
        return $this->clientMenus;
    }

    /**
     * @param mixed $orderEmail
     */
    public function setOrderEmail($orderEmail)
    {
        $this->orderEmail = $orderEmail;
    }

    /**
     * @return mixed
     */
    public function getOrderEmail()
    {
        return $this->orderEmail;
    }

    /**
     * @param mixed $suspendAfter
     */
    public function setSuspendAfter($suspendAfter)
    {
        $this->suspendAfter = $suspendAfter;
    }

    /**
     * @return mixed
     */
    public function getSuspendAfter()
    {
        return $this->suspendAfter;
    }

    /**
     * @param mixed $terminateAfter
     */
    public function setTerminateAfter($terminateAfter)
    {
        $this->terminateAfter = $terminateAfter;
    }

    /**
     * @return mixed
     */
    public function getTerminateAfter()
    {
        return $this->terminateAfter;
    }
}