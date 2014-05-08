<?php
namespace App\AdminBundle\Business\ProjectTracking;

use App\AdminBundle\Business\Base\BaseInlineFormHandler;
use App\ClientBundle\Entity;
use App\AdminBundle\Business\GlobalUtils;

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

        $idProject = $this->container->get('request')->query->get('id', 0);

        $start = $this->helperCommon->formatKendoDatetime($model['start']);
        $stop  = $this->helperCommon->formatKendoDatetime($model['stop']);

        $hourly = $model['hourly'];
        if (empty($model['hourly'])) {
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

        $tracking = new Entity\ClientProjectTracking();
        $tracking->setIdProject($idProject);
        $tracking->setStart($start);
        $tracking->setStop($stop);
        $tracking->setHourly($hourly);
        $tracking->setStaff($model['staff']);
        $tracking->setInvoiced(intval($model['invoiced']));

        $this->entityManager->persist($tracking);
        $this->entityManager->flush();

        $this->newEntity = $tracking;

        parent::onSuccess();
    }
}
