<?php

namespace App\ClientBundle\Business\TicketItem;


use App\ClientBundle\Business\Base\BaseCreateHandler;

use App\ClientBundle\Entity;
use App\AdminBundle\Business as AdminBusiness;


class CreateHandler extends BaseCreateHandler
{

    public function getDefaultValues()
    {

        $model = new CreateModel();

        $idTicket = $this->container->get('request')->query->get('id', 0);

        $ticket = $this->container->get('doctrine')->getRepository('AppClientBundle:Ticket')->findOneById($idTicket);


        $model->subject = $ticket->getSubject();

        //$model->status = $ticket->getStatus();

        return $model;

    }


    public function buildForm($builder)
    {

        /*

        $builder->add('subject', 'text', array(

            'attr'     => array(

                'placeholder' => 'Subject',

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

            'required' => true

        ));

        $builder->add('attachments', 'file_attachment', array(

            'attr'     => array(

                'placeholder' => "ATTACHMENT"

            ),

            'required' => false

        ));


        return $builder;

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();

        $idTicket = $this->container->get('request')->query->get('id', 0);


        $ticket = $this->container->get('doctrine')->getRepository('AppClientBundle:Ticket')->findOneById($idTicket);

        //$ticket->setStatus($model->status);

        //$ticket->setSubject($model->subject);

        $this->entityManager->persist($ticket);

        $this->entityManager->flush();


        $ticketResponse = new Entity\TicketResponse();

        $ticketResponse->setIdTicket($idTicket);

        $ticketResponse->setIdType(1);

        $ticketResponse->setIdUser($this->getUserId());

        $ticketResponse->setTimestamp(new \DateTime());

        $ticketResponse->setBody($model->body);

        $this->entityManager->persist($ticketResponse);

        $this->entityManager->flush();


        if (is_array($model->attachments)) {

            // Create upload directory

            $randString = $this->container->get('app_admin.helper.common')->generateRandString(16);

            $directory = $this->container->getParameter('upload_dir') . '/' . $randString;

            while (is_dir($directory)) {

                $randString = $container->get('app_admin.helper.common')->generateRandString(16);

                $directory = $this->container->getParameter('upload_dir') . '/' . $randString;

            }

            @mkdir($directory);


            foreach ($model->attachments as $uploadFile) {

                if ($uploadFile) {

                    $uploadFile->move($directory, $uploadFile->getClientOriginalName());


                    $trFile = new Entity\TicketResponseFile();

                    $trFile->setIdResponse($ticketResponse->getId());

                    $trFile->setFile($randString . '/' . $uploadFile->getClientOriginalName());

                    $this->entityManager->persist($trFile);

                }

            }

            $this->entityManager->flush();

        }

        // Log
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_TICKET_COMMENT, 'Ticket: ' . $ticket->getSubject());


        parent::onSuccess();

    }

}