<?php

namespace App\ClientBundle\Business\Profile;


use App\ClientBundle\Business\Base\BaseEditHandler;

use App\ClientBundle\Entity;
use App\AdminBundle\Business as AdminBusiness;


class EditHandler extends BaseEditHandler
{

    public function loadEntity()
    {

        return $this->helperDoctrine->findOneById('AppUserBundle:User', $this->getUserId());

    }

    public function getModelFromEntity($entity)
    {

        $model = new EditModel();

        $model->container = $this->container;

        $model->entityId = $entity->getId();

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

                'placeholder' => 'PHONE'

            ),

            'required' => false

        ));

        $builder->add('password', 'password', array(

            'attr'     => array(

                'placeholder' => 'PASSWORD'

            ),

            'required' => false

        ));

    }


    public function onSuccess()
    {

        $model = $this->getForm()->getData();

        $entity = $this->getEntity();

        $helperUser = $this->container->get('app_admin.helper.user');


        $currentPassword = $entity->getPassword();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);

        $entity->setPassword($currentPassword);


        if (trim($model->password) != '') {

            $entity->setPassword($helperUser->encodePassword($model->password));

            $this->messages[] = 'The password has been updated';

        }

        // Log
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_PROFILE_EDIT);

        parent::onSuccess();

    }

}