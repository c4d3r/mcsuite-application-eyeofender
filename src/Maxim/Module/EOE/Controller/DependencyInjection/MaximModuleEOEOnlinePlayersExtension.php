<?php
/**
 * Author: Maxim
 * Date: 30/12/13
 * Time: 22:25
 * Property of MCSuite
 */

namespace Maxim\Module\EOE\Controller\DependencyInjection;


use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;


class MaximModuleEOEOnlinePlayersExtension extends Extension
{
    public function load(array $config, ContainerBuilder $container)
    {
       // $configuration = new Configuration();

       // $config = $this->processConfiguration($configuration, $config);

       // $container->setParameter('maxim_module_forum.threads.threshold', $config['threads']['threshold']);
       // $container->setParameter('maxim_module_forum.posts.threshold', $config['posts']['threshold']);

    }
}