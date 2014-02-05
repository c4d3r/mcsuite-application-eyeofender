<?php
/**
 * Author: Maxim
 * Date: 14/12/13
 * Time: 17:39
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MaximModuleForumExtension extends Extension{

    /**
     * Loads a specific configuration.
     *
     * @param array $config An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     *
     * @api
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $config);

        $container->setParameter('maxim_module_forum.threads.threshold', $config['threads']['threshold']);
        $container->setParameter('maxim_module_forum.posts.threshold', $config['posts']['threshold']);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

    }
}