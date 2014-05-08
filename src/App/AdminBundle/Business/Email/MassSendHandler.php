<?php

namespace App\AdminBundle\Business\Email;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business\GlobalUtils;
use App\ClientBundle\Entity\ClientEmail;

class MassSendHandler extends BaseCreateHandler
{

    public function getDefaultValues()
    {
        $model = new MassSendModel();

        return $model;
    }

    public function buildForm($builder)
    {
        $builder->add('clients', 'choice', array(
            'attr'     => array(
                'placeholder' => 'CLIENT'
            ),
            'required' => true,
            'multiple' => 'multiple',
            'choices'  => $this->helperMapping->getMapping('client_list')
        ));
        $builder->add('subject', 'text', array(
            'attr'     => array(
                'placeholder' => 'SUBJECT'
            ),
            'required' => true
        ));

        $builder->add('body', 'texteditor', array(
            'attr'     => array(
                'placeholder' => 'BODY',
                'rows'        => 15,
                'style'       => 'width: 70%'
            ),
            'required' => false
        ));

        return $builder;
    }

    public function onSuccess()
    {
        $model     = $this->getForm()->getData();
        $env       = new \Twig_Environment(new \Twig_Loader_String());
        $formatter = $this->container->get('app_admin.helper.formatter');
        $config    = $this->container->get('app_admin.helper.common')->getConfig();

        foreach ($model->clients as $idClient) {
            $client = GlobalUtils::getClientById($this->container, $idClient);

            $data   = array(
                'firstName'  => $client->getFirstName(),
                'lastName'   => $client->getLastName(),
                'company'    => $client->getCompany(),
                'address1'   => $client->getAddress1(),
                'address2'   => $client->getAddress2(),
                'city'       => $client->getCity(),
                'state'      => $client->getState(),
                'postcode'   => $client->getPostcode(),
                'country'    => $formatter->format($client->getIdCountry(), 'mapping', $client->getIdCountry()),
                'phone'      => $client->getPhone(),
                'email'      => $client->getEmail(),
                'hourlyRate' => $formatter->format($client->getDefaultHourlyRate(), 'money'),
                'vatNumber'  => $client->getVatNumber()
            );

            $body = $env->render($model->body, $data);

            try {
                $senderEmail = $config->getDefaultEmail();
                $senderName  = $config->getBusinessName();
                $message     = \Swift_Message::newInstance()
                    ->setSubject($model->subject)
                    ->setFrom($senderEmail, $senderName)
                    ->setTo($client->getEmail())
                    ->setBody(html_entity_decode($body), 'text/html');
                $this->container->get('mailer')->send($message);

                // Log email
                $doctrine = $this->container->get('doctrine');
                $clientEmail = new ClientEmail();
                $clientEmail->setIdClient($client->getId());
                $clientEmail->setTimestamp(new \DateTime());
                $clientEmail->setSubject($model->subject);
                $doctrine->getManager()->persist($clientEmail);
                $doctrine->getManager()->flush();

            } catch (\Exception $e) {
                $this->errors[] = $e->getMessage();
            }
        }

        parent::onSuccess();
        $this->messages = array('EMAIL_HAS_BEEN_SENT');
    }
}