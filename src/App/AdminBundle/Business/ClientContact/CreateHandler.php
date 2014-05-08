<?php
namespace App\AdminBundle\Business\ClientContact;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;

class CreateHandler extends BaseCreateHandler
{
    public function getDefaultValues()
    {
        $model            = new CreateModel();
        $model->container = $this->container;

        return $model;
    }

    public function buildForm($builder)
    {
        $builder->add('firstname', 'text', array(
            'attr'     => array(
                'placeholder' => 'FIRST_NAME'
            ),
            'required' => false
        ));
        $builder->add('lastname', 'text', array(
            'attr'     => array(
                'placeholder' => 'LAST_NAME'
            ),
            'required' => false
        ));
        $builder->add('email', 'text', array(
            'attr'     => array(
                'placeholder' => 'EMAIL'
            ),
            'required' => false
        ));

        return $builder;
    }

    public function onSuccess()
    {
        $model      = $this->getForm()->getData();
        $helperUser = $this->container->get('app_admin.helper.user');

        $contact = new Entity\ClientContact();
        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $contact);
        $contact->setPassword($helperUser->encodePassword($model->password));
        $contact->setIdClient($this->container->get('request')->query->get('id', 0));

        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        $this->helperDoctrine->saveList(
            $contact,
            new Entity\ClientContactPermission(), $model->permissions, 'IdClientContact', 'IdPage'
        );
        parent::onSuccess();
    }
}