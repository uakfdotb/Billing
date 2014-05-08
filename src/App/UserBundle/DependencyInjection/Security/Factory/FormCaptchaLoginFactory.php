<?php

namespace App\UserBundle\DependencyInjection\Security\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\FormLoginFactory;


class FormCaptchaLoginFactory extends FormLoginFactory
{
    public function getKey()
    {
        return 'form_login_captcha';
    }

    public function getListenerId()
    {
        return 'app_user.username_password_captcha_listener';
    }
}