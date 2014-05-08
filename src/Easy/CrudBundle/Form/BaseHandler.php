<?php
namespace Easy\CrudBundle\Form;

abstract class BaseHandler implements FormHandlerInterface
{
    protected $controller;
    protected $form;
    protected $errors = array();
    protected $messages = array();

    public function __construct($controller)
    {
        $this->controller = $controller;
    }

    public function onSuccess()
    {
    }

    public function onFailure()
    {
        $this->errors[] = 'Error!';
        foreach ($this->getForm()->getErrors() as $key => $error) {
            $template   = $error->getMessageTemplate();
            $parameters = $error->getMessageParameters();
            foreach ($parameters as $var => $value) {
                $template = str_replace($var, $value, $template);
            }

            $this->errors[] = $template;
        }
    }

    public function getForm()
    {
        return $this->form;
    }

    public function getMessages()
    {
        return $this->messages;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    //abstract public function execute();
}

