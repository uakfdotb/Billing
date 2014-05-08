<?php

namespace App\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class MappingController extends BaseController
{
    public function getKendoMappingAction($code)
    {
        $withNull = $this->getRequest()->query->get('withNull', 0);
        $mapping  = $this->get('app_admin.helper.mapping')->getMapping($code);

        $arr = array();
        if ($withNull == 1) {
            $arr[] = array('value' => 0, 'text' => 'Please select');
        }
        foreach ($mapping as $value => $text) {
            $arr[] = array('value' => $value, 'text' => $text);
        }
        $total    = count($arr);
        $response = new Response(json_encode(array('data' => $arr, 'total' => $total)));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
