<?php
namespace App\AdminBundle\Business\ProjectTracking;

use App\AdminBundle\Business\Base\BaseInlineFormHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business\GlobalUtils;

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

        $idProject = $this->container->get('request')->query->get('id', 0);

        $start = $this->helperCommon->formatKendoDatetime($model['start']);
        $stop  = $this->helperCommon->formatKendoDatetime($model['stop']);

        $hourly = floatval($model['hourly']);
        if (empty($hourly)) {
            $doctrine = $this->container->get('doctrine');
            $staff    = $doctrine->getRepository('AppClientBundle:Admin')->findOneById($model['staff']);
            if ($staff) {
                $hourly = $staff->getHourly();
            }

            $project = $doctrine->getRepository('AppClientBundle:ClientProject')->findOneById($idProject);
            $client = GlobalUtils::getClientById($this->container, $project->getIdClient());
            if (trim($client->getDefaultHourlyRate()) != '') {
                $hourly = $client->getDefaultHourlyRate();
            }
        }

        $tracking = $this->helperDoctrine->findOneById('AppClientBundle:ClientProjectTracking', $model['id']);
        if ($start) {
            $tracking->setStart($start);
        }
        if ($stop) {
            $tracking->setStop($stop);
        }
        $tracking->setHourly($hourly);
        $tracking->setStaff($model['staff']);
        $tracking->setInvoiced($model['invoiced']);

        $this->entityManager->persist($tracking);
        $this->entityManager->flush();

        $this->entity = $tracking;

        parent::onSuccess();
    }
}