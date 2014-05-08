<?php
namespace App\AdminBundle\Business\Server;

use App\AdminBundle\Business\Base\BaseEditHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class EditHandler extends BaseEditHandler
{
    public function loadEntity()
    {
        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:Server');
    }

    public function getModelFromEntity($entity)
    {
        $model = new EditModel();

        $this->helperCommon->copyEntityToModel($this->entity, $model);

        $mcrypt               = $this->container->get('app_admin.helper.mcrypt');
        $model->encryptedIp   = $mcrypt->decrypt($model->encryptedIp);
        $model->encryptedUser = $mcrypt->decrypt($model->encryptedUser);
        $model->encryptedPass = $mcrypt->decrypt($model->encryptedPass);

        return $model;
    }

    public function buildForm($builder)
    {
        $builder->add('name', 'text', array(
            'attr'     => array(
                'placeholder' => 'NAME'
            ),
            'required' => true
        ));
        $builder->add('groupId', 'choice', array(
            'attr'     => array(
                'placeholder' => 'GROUP'
            ),
            'required' => false,
            'choices'  => $this->helperMapping->getMapping('server_group_list')
        ));
        $builder->add('encryptedIp', 'text', array(
            'attr'     => array(
                'placeholder' => 'IP'
            ),
            'required' => true
        ));
        $builder->add('encryptedUser', 'text', array(
            'attr'     => array(
                'placeholder' => 'USERNAME'
            ),
            'required' => true
        ));
        $builder->add('encryptedPass', 'text', array(
            'attr'     => array(
                'placeholder' => 'PASSWORD'
            ),
            'required' => true
        ));
        $builder->add('enabled', 'checkbox', array(
            'attr'     => array(
                'placeholder' => 'ENABLED',
            ),
            'required' => false
        ));
        return $builder;
    }

    public function onSuccess()
    {
        $model  = $this->getForm()->getData();
        $entity = $this->getEntity();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);
        $mcrypt = $this->container->get('app_admin.helper.mcrypt');
        $entity->setEncryptedIp($mcrypt->encrypt($model->encryptedIp));
        $entity->setEncryptedUser($mcrypt->encrypt($model->encryptedUser));
        $entity->setEncryptedPass($mcrypt->encrypt($model->encryptedPass));

        parent::onSuccess();
    }
}