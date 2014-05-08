<?php

namespace App\UserBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use App\UserBundle\DependencyInjection\Security\Factory\FormCaptchaLoginFactory;


class AppUserBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new FormCaptchaLoginFactory());
    }

    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
