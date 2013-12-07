<?php

namespace Maxim\CMSBundle\DependencyInjection;

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
        $rootNode = $treeBuilder->root('maxim_cms');

        $rootNode
            ->children()
            ->arrayNode('general')
                ->children()
                    ->scalarNode('default_role')
                        ->defaultValue('Member')
                    ->end()
                    ->scalarNode('domain')
                    ->end()
                    ->scalarNode('maintenance')
                        ->defaultValue('false')
                    ->end()
                ->end()
            ->end()
            ->arrayNode('emails')
                ->children()
                    ->scalarNode('info')->end()
                    ->scalarNode('support')->end()
                ->end()
            ->end()
            ->arrayNode('pages')
                ->children()
                    ->scalarNode('forum')->end()
                ->end()
            ->end()
            ->arrayNode('server')
                ->children()
                    ->scalarNode('name')->end()
                    ->scalarNode('ip')->end()
                    ->arrayNode('mysql')
                        ->children()
                            ->scalarNode('host')->end()
                            ->scalarNode('user')->end()
                            ->scalarNode('pass')->end()
                            ->scalarNode('db')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('account')
                ->children()
                    ->scalarNode('codeSalt')->end()
                    ->scalarNode('codeLength')->end()
                    ->arrayNode('send')
                        ->children()
                            ->scalarNode('enabled')->end()
                            ->scalarNode('command')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('forgotPassword')
                ->children()
                    ->scalarNode('salt')->end()
                    ->scalarNode('time')->end()
                    ->scalarNode('emailFrom')->end()
                ->end()
            ->end()
            ->arrayNode('register')
                ->children()
                    ->arrayNode('mail')
                        ->children()
                            ->scalarNode('enabled')->end()
                            ->scalarNode('subject')->end()
                            ->scalarNode('message')->end()
                        ->end()
                    ->end()
                    ->arrayNode('validation')
                        ->children()
                            ->scalarNode('enabled')->end()
                            ->scalarNode('salt')->end()
                            ->scalarNode('time')->end()
                            ->scalarNode('subject')->end()
                            ->scalarNode('message')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            // SHOP
            ->arrayNode('shop')
                ->children()
                    ->scalarNode('email')->end()
                    ->scalarNode('paypal')->end()
                    ->scalarNode('currency')->end()
                    ->scalarNode('currency_symbol')->end()
                    ->scalarNode('return')->end()
                    ->scalarNode('cancel')->end()
                    ->scalarNode('ipn')->end()
                ->end()
            ->end()
        ;
        return $treeBuilder;
    }
}
