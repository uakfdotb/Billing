<?php
namespace App\AdminBundle\Business\Server;

use App\AdminBundle\Business\Base\BaseCreateHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business;

class CreateHandler extends BaseCreateHandler
{
    public function getDefaultValues()
    {
        $model = new CreateModel();

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
        $mcrypt = $this->container->get('app_admin.helper.mcrypt');

        $item = new Entity\Server();
        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $item);

        // Set encrypted values
        $item->setEncryptedIp($mcrypt->encrypt($model->encryptedIp));
        $item->setEncryptedUser($mcrypt->encrypt($model->encryptedUser));
        $item->setEncryptedPass($mcrypt->encrypt($model->encryptedPass));

        $this->entityManager->persist($item);
        $this->entityManager->flush();

        parent::onSuccess();
    }
}