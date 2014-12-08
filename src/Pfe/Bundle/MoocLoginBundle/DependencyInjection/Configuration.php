<?php

namespace Pfe\Bundle\MoocLoginBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface {

    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder() {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('pfe_mooc_login');

        $rootNode
            ->children()
            ->scalarNode('username')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('password')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->arrayNode('ecampus')
            ->children()
            ->scalarNode('domain')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('url')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->end()
            ->end()
            ->arrayNode('mooc')
            ->children()
            ->scalarNode('base_url')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('domain')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('url')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('user_idp')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->end()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }

}
