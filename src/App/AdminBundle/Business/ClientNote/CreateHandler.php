<?php
namespace App\AdminBundle\Business\ClientNote;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;

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
        $builder->add('body', 'texteditor', array(
            'attr'     => array(
                'placeholder' => 'BODY',
                'rows'        => 15,
                'style'       => 'width: 70%'
            ),
            'required' => false
        ));
        $builder->add('isEncrypted', 'checkbox', array(
            'attr'     => array(
                'placeholder' => 'IS_ENCRYPTED'
            ),
            'required' => false
        ));

        return $builder;
    }

    public function onSuccess()
    {
        $model = $this->getForm()->getData();

        $note = new Entity\ClientNote();
        $note->setIdClient($this->container->get('request')->query->get('id', 0));
        $note->setSubject($model->subject);
        $note->setBody($model->body);
        $note->setTimestamp(new \DateTime());
        $note->setIsEncrypted($model->isEncrypted);
        if ($model->isEncrypted) {
            $mcrypt = $this->container->get('app_admin.helper.mcrypt');
            $note->setBody($mcrypt->encrypt($model->body));
        }

        $this->entityManager->persist($note);
        $this->entityManager->flush();

        parent::onSuccess();
    }


    public function isAccept()
    {
        $request = $this->container->get('request');
        if ($request->getMethod() == 'POST') {
            $form = $request->request->get('form');
            if (isset($form['body'])) {
                return true;
            }
            return false;
        }

        return false;
    }
}