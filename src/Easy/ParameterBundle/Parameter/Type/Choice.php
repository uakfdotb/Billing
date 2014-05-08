<?php
namespace Easy\ParameterBundle\Parameter\Type;

use Easy\ParameterBundle\Parameter\ParameterInterface;
use Symfony\Component\Validator\Constraints\MinLength;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Choice as ChoiceConstraint;
use Symfony\Component\Validator\Constraints\Collection;

class Choice implements ParameterInterface
{
    public $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function buildForm(&$builder, $config)
    {
        $mapping = $this->container->get('easy_mapping');
        $choices = $mapping->getMapping($config['mapping']);

        $builder->add('value', 'choice', array(
            'label'   => $config['label'],
            'choices' => $choices
        ));
    }

    public function view($data, $config)
    {
        $mapping = $this->container->get('easy_mapping');
        return $mapping->getMappingTitle($config['mapping'], $data['value']);
    }
}
