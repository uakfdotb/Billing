<?php
namespace App\AdminBundle\Business\ProjectTask;

use App\AdminBundle\Business\Base\BaseInlineFormHandler;
use App\ClientBundle\Entity;

class EditHandler extends BaseInlineFormHandler
{
    public $entity;

    public function getDefaultValues()
    {
        $model = new EditModel();

        return $model;
    }

    public function onSuccess()
    {
        $model = $this->container->get('request')->query->get('models');
        $model = $model[0];

        $timestamp = $this->helperCommon->formatKendoDatetime($model['timestamp']);

        $task = $this->helperDoctrine->findOneById('AppClientBundle:ClientProjectTask', $model['id']);
        $task->setSubject($model['subject']);
        if ($timestamp) {
            $task->setTimestamp($timestamp);
        }
        $task->setIdWorkType($model['idWorkType']);
        $task->setUnit($model['unit']);
        $task->setQuantity($model['quantity']);
        $task->setUnitPrice($model['unitPrice']);
        $task->setIsBillable(strcmp($model['isBillable'], 'true') === 0);
        $task->setStatus($model['status']);
        //$task->setInvoiced($model['invoiced']);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $this->entity = $task;

        parent::onSuccess();
    }
}