<?php

namespace App\ClientBundle\Business\Ticket;


use App\ClientBundle\Business\Base\BaseCreateHandler;

use App\ClientBundle\Entity;
use App\AdminBundle\Business as AdminBusiness;


class CreateHandler extends BaseCreateHandler
{

    public function getDefaultValues()
    {

        $model = new CreateModel();


        return $model;

    }


    public function buildForm($builder)
    {

        $builder->add('subject', 'text', array(

            'attr'     => array(

                'placeholder' => 'SUBJECT'

            ),

            'required' => true

        ));

        /*

        $builder->add('status', 'choice', array(

            'attr'     => array(

                'placeholder' => 'Status'

            ),

            'required' => true,

            'choices'  => $this->helperMapping->getMapping('ticket_status')

        ));

        */

        $builder->add('body', 'texteditor', array(

            'attr'     => array(

                'placeholder' => 'BODY',

                'rows'        => 10,

                'style'       => 'width: 70%'

            ),

            'required' => true,

        ));

        return $builder;

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();


        $ticket = new Entity\Ticket();

        $ticket->setIdClient($this->getUserId());

        $ticket->setSubject($model->subject);

        $ticket->setStatus(1);


        $this->entityManager->persist($ticket);

        $this->entityManager->flush();


        $response = new Entity\TicketResponse();

        $response->setTimestamp(new \DateTime());

        $response->setIdUser($this->getUserId());

        $response->setBody($model->body);

        $response->setIdTicket($ticket->getId());

        $this->entityManager->persist($response);

        $this->entityManager->flush();

        // Log
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_TICKET_CREATE, 'Subject: ' . $ticket->getSubject());

        parent::onSuccess();

    }

}