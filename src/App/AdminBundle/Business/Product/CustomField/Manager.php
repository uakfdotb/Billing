<?php
namespace App\AdminBundle\Business\Product\CustomField;

use App\AdminBundle\Business;

class Manager
{
    protected $container;
    protected $handlers = array();
    protected $module;

    public function __construct($container, $module = null)
    {
        $allModules          = Business\Product\Constants::getProductTypes();
        $this->container     = $container;
        $this->module        = $module;
        $modules             = isset($module) ? [$module => $allModules[$module]] : $allModules;

        foreach($modules as $number => $name)
        {
            $className = sprintf("\\App\\AdminBundle\\Helper\\Provision\\%s\\%s", ucfirst($name), $name);
            $method    = sprintf("%s_ConfigOptions", $name);
            $class     = new $className($this->container);

            $this->handlers[$number] = $class->$method();
        }
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function buildForm($builder)
    {
        foreach($this->handlers as $handler)
        {
            foreach($handler as $field => $data)
            {
                $fieldData = array(
                    'attr'     => array(
                        'placeholder' => isset($data['Description']) ? $data['Description'] : null
                    ),
                    'label' => isset($data['FriendlyName']) ? $data['FriendlyName'] : $field,
                    'mapped' => false,
                    'required' => false
                );
                if(isset($data['Options']))
                {
                    $choices = explode(',', $data['Options']);
                    foreach($choices as $choice)
                    {
                        $fieldData['choices'][$choice] = $choice;
                    }
                }

                $builder->add(str_replace(' ', '_', $field), $this->adaptModuleFieldType($data['Type']), $fieldData);
            }
        }

        return $builder;
    }

    public function saveData($formData)
    {
        $array = array();
        foreach($this->handlers[$this->module] as $key => $data)
        {
            $array[$key] = isset($formData[$key]) ? $formData[$key] : 0;
        }

        return json_encode($array);
    }

    public function loadData($model, $data)
    {
        $array = json_decode($data);

        foreach($array as $variable => $value)
        {
            $model->$variable = $value;
        }

        return $model;
    }

    private function adaptModuleFieldType($type)
    {
        if($type == "yesno") return "checkbox";
        if($type == "dropdown") return "choice";
        return $type;
    }
}