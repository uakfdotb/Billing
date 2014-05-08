<?php
namespace App\ClientBundle\Business\Contact;

use App\ClientBundle\Business\Base\BaseEditHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business as AdminBusiness;

class EditHandler extends BaseEditHandler
{
    public function loadEntity()
    {
        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:ClientContact');
    }
    public function getModelFromEntity($entity)
    {
        $model = new EditModel();
        $model->container = $this->container;
        $model->entityId = $entity->getId();

        $this->helperCommon->copyEntityToModel($this->entity, $model);

        //$model->permissions = $this->helperDoctrine->loadList('AppClientBundle:ClientContactPermission', 'idClientContact', 'idPage', $entity->getId());
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
    }

    public function onSuccess()
    {
        $model = $this->getForm()->getData();
        $entity = $this->getEntity();
        $helperUser = $this->container->get('app_admin.helper.user');

        $currentPassword = $entity->getPassword();
        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);
        /*$entity->setPassword($currentPassword);

        if (trim($model->password) != '') {
            $entity->setPassword($helperUser->encodePassword($model->password));
            $this->messages[] = 'The password has been updated';
        }
        $this->helperDoctrine->deleteList('AppClientBundle:ClientContactPermission', 'idClientContact', $entity->getId());
        $this->helperDoctrine->saveList(
            $entity,
            new Entity\ClientContactPermission(), $model->permissions, 'idClientContact', 'idPage'
        );*/

        // Log
        $this->container->get('app_client.helper.client_log')->log(AdminBusiness\ClientLog\Constants::LOG_TYPE_CONTACT_EDIT, 'Contact email: ' . $entity->getEmail());
        parent::onSuccess();
    }
}