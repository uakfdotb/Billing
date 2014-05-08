<?php

namespace App\AdminBundle\Business\Staff;


use App\AdminBundle\Business\Base\BaseEditHandler;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\ClientBundle\Entity;


class EditHandler extends BaseEditHandler
{

    public function loadEntity()
    {
        $entity = $this->helperDoctrine->findOneByRequestIdControl('AppUserBundle:User');
        if(!$entity) throw new AccessDeniedException;
        return $entity;

    }

    public function getModelFromEntity($entity)
    {

        $model = new EditModel();

        $this->helperCommon->copyEntityToModel($this->entity, $model);


        //$model->permissions = $this->helperDoctrine->loadList('AppClientBundle:AdminPermission', 'idAdmin', 'idPage', $entity->getId());


        return $model;

    }


    public function buildForm($builder)
    {


        $builder->add('firstname', 'text', array(

            'attr'     => array(

                'placeholder' => 'FIRST_NAME'

            ),

            'required' => true

        ));

        $builder->add('lastname', 'text', array(

            'attr'     => array(

                'placeholder' => 'LAST_NAME'

            ),

            'required' => true

        ));

        $builder->add('plainPassword', 'password', array(

            'attr'     => array(

                'placeholder' => 'PASSWORD'

            ),

            'required' => false

        ));

        $builder->add('defaultHourlyRate', 'money', array(

            'attr'     => array(

                'placeholder' => 'HOURLY'

            ),

            'currency' => $this->helperCommon->getConfig()->getBillingCurrency(),

            'required' => false

        ));

        $builder->add('permissionGroup', 'choice', array(

            'attr'     => array(

                'placeholder' => 'PERMISSION_GROUP'

            ),

            'required' => false,

            'choices'  => $this->helperMapping->getMapping('permission_group_list')

        ));
        /*$builder->add('ip', 'text', array(

            'attr'     => array(

                'placeholder' => 'IP_START'

            ),

            'required' => false

        ));
        $builder->add('ipend', 'text', array(

            'attr'     => array(

                'placeholder' => 'IP_END'

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

            'choices'  => $this->helperMapping->getMapping('staff_permission')

        ));*/

    }


    public function onSuccess()
    {
        $model      = $this->getForm()->getData();
        $entity     = $this->getEntity();
        $helperUser = $this->container->get('app_admin.helper.user');

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);

        if (trim($model->password) != '') {
            $entity->setPassword($helperUser->encodePassword($model->password));
            $this->messages[] = 'THE_PASSWORD_HAS_BEEN_UPDATED';
        }

        $this->messages[] = 'THE_RECORD_HAS_BEEN_UPDATED_SUCCESSFULLY';

        $em = $this->container->get('doctrine')->getEntityManager('control');

        if (is_array($this->getEntity())) {
            foreach ($this->getEntity() as $e) {
                $em->persist($e);
            }
        } else {
            $em->persist($this->getEntity());
        }

        $em->flush();
    }

}