<?php
/**
 * Author: Maxim
 * Date: 14/12/13
 * Time: 17:37
 * Property of MCSuite
 */
namespace Maxim\Module\ForumBundle\DependencyInjection;

    use Symfony\Component\Config\Definition\Builder\TreeBuilder;
    use Symfony\Component\Config\Definition\ConfigurationInterface;

    /**
     * This is the class that validates and merges configuration from your app/config files
     *
     * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
     */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('maxim_module_forum');

        $rootNode
            ->children()
            ->arrayNode('threads')
                ->children()
                    ->scalarNode('threshold')->end()
                ->end()
            ->end()
            ->arrayNode('posts')
                ->children()
                    ->scalarNode('threshold')->end()
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }

} 