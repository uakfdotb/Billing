<?php

namespace App\AdminBundle\Form\Type;


use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilder;

use Symfony\Component\Form\FormInterface;

use Symfony\Component\Form\FormView;


class DateTimePicker extends AbstractType
{

    public $container;

    public function __construct($container)
    {

        $this->container = $container;

    }


    public function getDefaultOptions(array $options)

    {

        $config = $this->container->get('app_admin.helper.common')->getConfig();

        $format = $this->container->get('app_admin.helper.mapping')->getMappingTitle('config_symfony_date_picker_format', $config->getDateFormat());


        return array(

            'widget' => 'single_text',

            'format' => $format . ' HH:mm:ss'

        );

    }


    public function finishView(FormView $view, FormInterface $form, array $options)

    {

        $config = $this->container->get('app_admin.helper.common')->getConfig();

        $format = $this->container->get('app_admin.helper.mapping')->getMappingTitle('config_kendo_date_picker_format', $config->getDateFormat());


        parent::finishView($view, $form, $options);

        $view->vars['format'] = $format . ' hh:mm:ss';

    }


    public function getParent()

    {

        return 'datetime';

    }


    public function getName()

    {

        return 'datetime_picker';

    }

}