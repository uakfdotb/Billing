<?php
namespace Easy\CrudBundle\Form;

interface FormHandlerInterface
{
    function onSuccess();

    function onFailure();

    function getErrors();

    function getMessages();

    function getForm();

    function execute();
}
