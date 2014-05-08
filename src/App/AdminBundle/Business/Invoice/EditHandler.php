<?php

namespace App\AdminBundle\Business\Invoice;

use App\AdminBundle\Business\Base\BaseEditHandler;
use App\ClientBundle\Entity;
use App\ClientBundle\Entity\ClientInvoice;
use App\ClientBundle\Entity\Config;
use App\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Doctrine\Bundle\DoctrineBundle\Command\CreateDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\DropDatabaseDoctrineCommand;
use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand;
use FOS\UserBundle\Model\UserManager;
use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\DBAL\Driver\Mysqli\MysqliException;
use Doctrine\ORM\EntityManager;
use App\ClientBundle\ClientEvents;


class EditHandler extends BaseEditHandler
{
    public function loadEntity()
    {
        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:ClientInvoice');
    }

    public function getModelFromEntity($entity)
    {
        $model = new EditModel();
        $this->helperCommon->copyEntityToModel($this->entity, $model);

        return $model;
    }


    public function buildForm($builder)
    {
        $builder->add('idClient', 'choice', array(
            'attr'     => array(
                'placeholder' => 'CLIENT',
            ),
            'label' => 'Client',
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('client_list')
        ));
        $builder->add('subject', 'text', array(
            'attr'     => array(
                'placeholder' => 'SUBJECT'
            ),
            'label' => 'Subject',
            'required' => false
        ));
        $builder->add('issueDate', 'date_picker', array(
            'attr'     => array(
                'placeholder' => 'ISSUE_DATE'
            ),
            'required' => false,
            'label' => 'Issue Date',
            'widget'   => 'single_text'
        ));

        $builder->add('dueDate', 'date_picker', array(
            'attr'     => array(
                'placeholder' => 'DUE_DATE'
            ),
            'label' => 'Due Date',
            'required' => false,
            'widget'   => 'single_text'
        ));
        $builder->add('discount', 'percent', array(
            'label' => 'Discount',
            'required' => false
        ));

        $builder->add('tax', 'choice', array(
            'attr'     => array(
                'placeholder' => 'TAX GROUP'
            ),
            'label' => 'Tax',
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('tax_list')
        ));

        $builder->add('notes', 'textarea', array(
            'attr'     => array(
                'placeholder' => 'NOTES'
            ),
            'label' => 'Notes',
            'required' => false
        ));
        $builder->add('status', 'choice', array(
            'attr'     => array(
                'placeholder' => 'STATUS'
            ),
            'label' => 'Status',
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('invoice_status')
        ));

        return $builder;
    }

    public function onSuccess()
    {
        $model  = $this->getForm()->getData();
        $entity = $this->getEntity();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);

        /** @var Config $config */
        $config = $this->entityManager->getRepository('AppClientBundle:Config')->find(1);

        $status = $entity->getStatus();
        if ($status == ClientInvoice::STATUS_PAID) {
            $paidCount = $config->getCountProformaPaidInvoice();
            $number = Utils::beautifyId($paidCount, $config->getInvoicePrefix());
            $entity->setNumber($number);

            $config->setCountProformaPaidInvoice(++$paidCount);

            $this->entityManager->persist($config);
            $this->entityManager->flush();
        }

        Utils::updateInvoiceStatus($this->container, $entity->getId());

        parent::onSuccess();
    }
}
