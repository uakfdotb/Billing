<?php
namespace App\AdminBundle\Business\ClientNote;

use App\AdminBundle\Business\Base\BaseEditHandler;
use App\ClientBundle\Entity;

class EditHandler extends BaseEditHandler
{
    public function loadEntity()
    {
        return $this->helperDoctrine->findOneByRequestId('AppClientBundle:ClientNote');
    }

    public function getModelFromEntity($entity)
    {
        $model = new EditModel();

        $this->helperCommon->copyEntityToModel($this->entity, $model);
        if ($entity->getIsEncrypted() == 1) {
            $mcrypt      = $this->container->get('app_admin.helper.mcrypt');
            $model->body = $mcrypt->decrypt($model->body);
        }

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
    }

    public function onSuccess()
    {
        $model  = $this->getForm()->getData();
        $entity = $this->getEntity();

        $this->container->get('app_admin.helper.common')->copyModelToEntity($model, $entity);
        if ($model->isEncrypted) {
            $mcrypt = $this->container->get('app_admin.helper.mcrypt');
            $entity->setBody($mcrypt->encrypt($model->body));
        }

        parent::onSuccess();
    }
}