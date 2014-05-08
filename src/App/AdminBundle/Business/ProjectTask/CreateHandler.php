<?php
namespace App\AdminBundle\Business\ProjectTask;

use App\AdminBundle\Business\Base\BaseInlineFormHandler;
use App\ClientBundle\Entity;

class CreateHandler extends BaseInlineFormHandler
{
    public $newEntity;

    public function getDefaultValues()
    {
        $model = new CreateModel();

        return $model;
    }

    public function onSuccess()
    {
        $model = $this->container->get('request')->query->get('models');
        $model = $model[0];

        $timestamp = $this->helperCommon->formatKendoDatetime($model['timestamp']);

        $task = new Entity\ClientProjectTask();
        $task->setIdProject($this->container->get('request')->query->get('id', 0));
        $task->setIdWorkType($model['idWorkType']);
        $task->setSubject($model['subject']);
        $task->setTimestamp($timestamp);
        $task->setUnit($model['unit']);
        $task->setQuantity($model['quantity']);
        $task->setUnitPrice($model['unitPrice']);
        $task->setIsBillable(strcmp($model['isBillable'], 'true') === 0);
        $task->setInvoiced(0);
        $task->setStatus($model['status']);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $this->newEntity = $task;

        parent::onSuccess();
    }
}
