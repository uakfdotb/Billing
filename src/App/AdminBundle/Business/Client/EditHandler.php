<?php
namespace App\AdminBundle\Business\Client;

use App\AdminBundle\Business\Base\BaseEditHandler;
use App\ClientBundle\Entity;
use FOS\UserBundle\Util\Canonicalizer as Canonicalizer;

class EditHandler extends BaseEditHandler
{
    public function loadEntity()
    {
        return $this->helperDoctrine->findOneByRequestId('AppUserBundle:User');
    }

    public function getModelFromEntity($entity)
    {
        $model            = new EditModel();
        $model->container = $this->container;
        $model->entityId  = $entity->getId();

        $this->helperCommon->copyEntityToModel($this->entity, $model);

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
        $builder->add('company', 'text', array(
            'attr'     => array(
                'placeholder' => 'COMPANY'
            ),
            'required' => false
        ));
        $builder->add('address1', 'text', array(
            'attr'     => array(
                'placeholder' => 'ADDRESS_1'
            ),
            'required' => false
        ));
        $builder->add('address2', 'text', array(
            'attr'     => array(
                'placeholder' => 'ADDRESS_2'
            ),
            'required' => false
        ));
        $builder->add('city', 'text', array(
            'attr'     => array(
                'placeholder' => 'CITY'
            ),
            'required' => false
        ));
        $builder->add('state', 'text', array(
            'attr'     => array(
                'placeholder' => 'STATE'
            ),
            'required' => false
        ));
        $builder->add('postcode', 'text', array(
            'attr'     => array(
                'placeholder' => 'POST_CODE'
            ),
            'required' => false
        ));
        $builder->add('idCountry', 'choice', array(
            'attr'     => array(
                'placeholder' => 'COUNTRY'
            ),
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('country')
        ));
        $builder->add('phone', 'text', array(
            'attr'     => array(
                'placeholder' => 'PHONE_NUMBER'
            ),
            'required' => false
        ));
        $builder->add('email', 'text', array(
            'attr'     => array(
                'placeholder' => 'EMAIL_ADDRESS'
            ),
            'required' => false
        ));
        $builder->add('vatNumber', 'text', array(
            'attr'     => array(
                'placeholder' => 'VAT_NUMBER',
            ),
            'required' => false
        ));
        $builder->add('status', 'choice', array(
            'attr'     => array(
                'placeholder' => 'STATE'
            ),
            'required' => true,
            'choices'  => $this->helperMapping->getMapping('client_status')
        ));
        $builder->add('plainPassword', 'password', array(
            'attr'     => array(
                'placeholder' => 'PASSWORD',
            ),
            'required' => true
        ));
    }

    public function onSuccess()
    {
        $model         = $this->getForm()->getData();
        $entity        = $this->getEntity();
        $helperUser    = $this->container->get('app_admin.helper.user');
        $canonicalizer = new Canonicalizer();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);
        $entity->setStatus($entity->getStatus());

        // Set canonical fields
        $entity->setUsername($entity->getEmail());
        $entity->setUsernameCanonical($canonicalizer->canonicalize($entity->getEmail()));
        $entity->setEmailCanonical($canonicalizer->canonicalize($entity->getEmail()));

        // Set password
        $salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $entity->setSalt($salt);
        $encoder_service = $this->container->get('security.encoder_factory');
        $encoder = $encoder_service->getEncoder($entity);
        $encoded_pass = $encoder->encodePassword($model->plainPassword, $salt);
        if(!empty($model->plainPassword)) $entity->setPassword($encoded_pass);

        $this->messages[] = 'THE_RECORD_HAS_BEEN_UPDATED_SUCCESSFULLY';

        $em = $this->container->get('doctrine')->getEntityManager();

        if (is_array($this->getEntity())) {
            foreach ($this->getEntity() as $e) {
                $em->persist($e);
            }
        } else {
            $em->persist($this->getEntity());
        }

        $em->flush();
    }

    public function isAccept()
    {
        $request = $this->container->get('request');
        if ($request->getMethod() == 'POST') {
            $form = $request->request->get('form');
            if (isset($form['firstname'])) {
                return true;
            }
            return false;
        }

        return false;
    }
}