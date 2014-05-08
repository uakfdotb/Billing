<?php
namespace App\ClientBundle\Business\Contact;

use App\ClientBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business as AdminBusiness;

class CreateHandler extends BaseCreateHandler
{
    public function getDefaultValues()
    {
        $model = new CreateModel();
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
        /*$builder->add('password', 'password', array(
            'attr'     => array(
                'placeholder' => 'PASSWORD'
            ),
            'required' => false
        ));

        $builder->add('permissions', 'choice', array(
            'attr'     => array(
                'placeholder' => 'PERMISSIONS'
            ),
            'required' => false,
            'expanded' => 'expanded',
            'multiple' => 'multiple',
            'choices'  => $this->helperMapping->getMapping('contact_permission')
        ));*/
        return $builder;
    }

    public function onSuccess()
    {
        $model = $this->getForm()->getData();
        $helperUser = $this->container->get('app_admin.helper.user');

        $contact = new Entity\ClientContact();
        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $contact);
        //$contact->setPassword($helperUser->encodePassword($model->password));
        $contact->setIdClient($this->getUserId());

        $this->entityManager->persist($contact);
        $this->entityManager->flush();

        /*$this->helperDoctrine->saveList(
            $contact,
            new Entity\ClientContactPermission(), $model->permissions, 'IdClientContact', 'IdPage'
        );*/
        // Log
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_CONTACT_CREATE, 'Contact email: ' . $model->email);
        parent::onSuccess();
    }
}