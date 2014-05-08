<?php

namespace Easy\BlockBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EasyBlockExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $easyBlockConfig = array();
        if (isset($config['block'])) {
            $easyBlockConfig['block'] = $config['block'];
        }
        if (isset($config['template'])) {
            $easyBlockConfig['template'] = $config['template'];
        }
        if (isset($config['page'])) {
            $easyBlockConfig['page'] = $config['page'];
        }
        if (isset($config['nocache'])) {
            $easyBlockConfig['nocache'] = $config['nocache'];
        }
        $container->setParameter('easy_block.config', $easyBlockConfig);
    }
}
