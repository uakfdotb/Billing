<?php

namespace App\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    public $viewTemplate = null;

    public function executeFunction($func, &$data, $action)
    {
        if (method_exists($this, $func)) {
            $this->$func($data, $action);
        }
    }
}
