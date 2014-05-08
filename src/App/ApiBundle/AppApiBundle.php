<?php

namespace App\ApiBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use App\ApiBundle\DependencyInjection\Security\Factory\ApiHttpBasicFactory;

class AppApiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $extension = $container->getExtension('security');
        $extension->addSecurityListenerFactory(new ApiHttpBasicFactory());
    }
}
