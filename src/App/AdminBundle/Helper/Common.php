<?php
namespace App\AdminBundle\Helper;

class Common
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function copyModelToEntity($model, $entity)
    {
        $modelVars = get_object_vars($model);
        if (is_array($modelVars)) {
            foreach ($modelVars as $key => $value) {
                $method = 'set' . ucfirst($key);
                if (method_exists($entity, $method)) {
                    $entity->$method($value);
                }
            }
        }
    }

    public function copyEntityToArray($entity)
    {
        $entityMethods = get_class_methods($entity);
        if (is_array($entityMethods)) {
            foreach ($entityMethods as $method) {
                if (preg_match('/get(.+)/ism', $method, $arr)) {
                    if (isset($arr[1])) {
                        $array[lcfirst($arr[1])] = $entity->$method();
                    }
                }
            }
        }
        return $array;
    }

    public function copyArrayToEntity($array, $entity)
    {
        if (is_array($array)) {
            foreach ($array as $k => $v) {
                $setMethod = 'set' . ucfirst($k);
                if (method_exists($entity, $setMethod)) {
                    $entity->$setMethod($v);
                }
            }
        }
    }

    public function copyArrayToModel($array, $model)
    {
        foreach ($array as $k => $v) {
            $model->$k = $v;
        }
    }

    public function copyEntityToModel($entity, $model)
    {
        $entityMethods = get_class_methods($entity);
        if (is_array($entityMethods)) {
            foreach ($entityMethods as $method) {
                if (preg_match('/get(.+)/ism', $method, $arr)) {
                    if (isset($arr[1])) {
                        $var         = lcfirst($arr[1]);
                        $model->$var = $entity->$method();
                    }
                }
            }
        }
    }

    public function generateRandString($length)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str   = substr(str_shuffle($chars), 0, $length);
        $size  = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }

        return $str;
    }

    public function generateOrderNumber($length)
    {
        $isFound = false;
        $chars   = "0123456789";
        $size    = strlen($chars);
        while ($isFound === false) {
            $str = '';
            for ($i = 0; $i < $length; $i++) {
                $str .= $chars[rand(0, $size - 1)];
            }

            $order = $this->container->get('doctrine')->getRepository('AppClientBundle:ProductOrder')->findOneByOrderNumber($str);
            if (!$order) {
                $isFound = true;
            }
        }

        return $str;
    }

    public function getConfig()
    {
        $config = $this->container->get('doctrine')->getRepository('AppClientBundle:Config')->findAll();
        foreach ($config as $c) {
            return $c;
        }
        return null;
    }


    public function getGatewayConfig()
    {
        // Deprecated
        return null;
    }

    public function formatKendoDatetime($datetime)
    {
        if (strlen($datetime) < 32) {
            return null;
        }
        return new \DateTime(substr($datetime, 0, 33));
    }

    public function populateFromRequest($model)
    {
        $request = $this->container->get('request');
        $arr     = get_object_vars($model);
        foreach ($arr as $k => $v) {
            $model->$k = $request->get($k, null);
        }
        return $model;
    }

    public function cleanup($array)
    {
        foreach ($array as $k => $v) {
            if ($v === null) {
                unset($array[$k]);
            }
        }
    }

    public function getErrorMessages($errors)
    {
        $errorMessages = array();
        foreach ($errors as $key => $error) {
            $template   = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();

            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $errorMessages[] = $error->getPropertyPath() . ':' . $template;
        }

        return $errorMessages;
    }
}
