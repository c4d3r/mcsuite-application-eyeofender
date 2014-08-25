<?php

namespace Maxim\CMSBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MaximCMSExtension extends Extension
{

    /*
     * load Parameters by recursion
     */
    public function loadParameters(ContainerBuilder $container, $parameters, $path) {

        foreach ($parameters as $key => $value) {

            $newPath = $path . '.' . $key ;

            $container->setParameter($newPath, $value);

            if(is_array($container->getParameter($newPath))) {

                $this->loadParameters($container, $container->getParameter($newPath), $newPath);

            }

        }

    }
    /**
     * Load all bundles there services.yml
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter("maxim_cms", $config);
        $this->loadParameters($container, $config, 'maxim_cms');

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
    public function getAlias()
    {
        return 'maxim_cms';
    }
}
