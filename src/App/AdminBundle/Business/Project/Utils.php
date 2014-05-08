<?php

namespace App\AdminBundle\Business\Project;

use App\ClientBundle\Entity;
use App\AdminBundle\Business\GlobalUtils;

class Utils
{
    public static function getStaffName($container, $admin)
    {
        return $admin->getFirstName() . ' ' . $admin->getLastName();
    }

    public static function startProjectTracking($controller, $idProject)
    {
        $userSession = $controller->get('app_admin.helper.user')->getUserSession();
        $staff       = $userSession;
        $hourly      = $staff->getDefaultHourlyRate();

        $doctrine = $controller->getDoctrine();
        $project  = $doctrine->getRepository('AppClientBundle:ClientProject')->findOneById($idProject);

        /*if($project->getIdClient())
        {
            $client = GlobalUtils::getClientById($controller->getContainer(), $project->getIdClient());
            if (trim($client->getHourly()) != '') {
                $hourly = $client->getDefaultHourlyRate();
            }
        }*/

        $em = $doctrine->getEntityManager();

        $projectTracking = new Entity\ClientProjectTracking();
        $projectTracking->setIdProject($idProject);
        $projectTracking->setStart(new \DateTime());
        $projectTracking->setStop(null);
        $projectTracking->setInvoiced(0);
        $projectTracking->setStaff($staff->getId());
        $projectTracking->setHourly(is_null($hourly) ? 0 : $hourly);

        $em->persist($projectTracking);
        $em->flush();

        return true;
    }

    public static function stopProjectTracking($controller, $idProject)
    {
        $doctrine = $controller->getDoctrine();
        $em       = $doctrine->getEntityManager();

        $cProjectTracking = $doctrine->getRepository('AppClientBundle:ClientProjectTracking')->findOneBy(array(
            'stop'      => null,
            'idProject' => $idProject
        ));
        if ($cProjectTracking) {
            $cProjectTracking->setStop(new \DateTime());
            $em->persist($cProjectTracking);
            $em->flush();
            return true;
        }
        return false;
    }
}