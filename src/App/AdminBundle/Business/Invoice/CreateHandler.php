<?php
namespace App\AdminBundle\Business\Invoice;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;
use App\ClientBundle\Entity\ClientInvoice;
use App\ClientBundle\Entity\Config;

class CreateHandler extends BaseCreateHandler
{
    public function getDefaultValues()
    {
        $model = new CreateModel();

        $model->idClient = $this->container->get('request')->query->get('id', null);

        return $model;
    }

    public function buildForm($builder)
    {
        $builder->add('idClient', 'choice', array(
            'attr'     => array(
                'placeholder' => 'CLIENT'
            ),
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('client_list')
        ));

        return $builder;
    }

    public function onSuccess()
    {
        $helperCommon = $this->container->get('app_admin.helper.common');

        $number = '';
        /** @var Config $config */
        $config = $this->entityManager->getRepository('AppClientBundle:Config')->find(1);
        $proformaEnabled = $config->getIsProformaInvoiceEnabled();

        $model = $this->getForm()->getData();

        $invoice = new Entity\ClientInvoice();
        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $invoice);
        $invoice->setSubject('');
        $invoice->setStatus(ClientInvoice::STATUS_UNPAID);
        $invoice->setTotalAmount(0);
        $invoice->setTotalPayment(0);

        $subdomain = $this->container->getParameter('client_subdomain');
        $invoice->setInvoiceAccessToken($helperCommon->generateRandString(16));

        if ($proformaEnabled) {
            $invoice->setStatus(ClientInvoice::STATUS_PROFORMA);
            $prefix = $config->getProformaInvoicePrefix();
            $count = $config->getCountProformaInvoice();

            $number = Utils::beautifyId($count, $prefix);
            $invoice->setNumber($number);

            $count++;
            $config->setCountProformaInvoice($count);

            $this->entityManager->persist($config);
            $this->entityManager->flush();
        }

        $invoice->setNotes($config->getInvoiceNotes());

        $this->entityManager->persist($invoice);
        $this->entityManager->flush();

        $this->newId = $invoice->getId();

        if ($number == '') {
            Utils::updateInvoicePrefix($this->container, $invoice->getId());
        }

        parent::onSuccess();
    }
}
