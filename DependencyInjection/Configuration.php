<?php

namespace Socloz\FeatureFlagBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('socloz_feature_flag');

        $rootNode
            ->children()
                ->arrayNode('services')->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('storage')->defaultValue('socloz_feature_flag.storage.redis')->end()
                        ->scalarNode('transaction')->defaultValue('socloz_feature_flag.transaction.session')->end()
                        ->scalarNode('analytics')->defaultValue('socloz_feature_flag.analytics.google_analytics')->end()
                        ->variableNode('state')->defaultValue(array('socloz_feature_flag.state.request', 'socloz_feature_flag.state.session'))->end()
                        ->scalarNode('splitter')->defaultValue('socloz_feature_flag.splitter.random')->end()
                        ->arrayNode('weight_feature')
                            ->defaultValue(array())
                            ->useAttributeAsKey('feature_name')
                            ->prototype('array')
                                ->useAttributeAsKey('variation_name')
                                ->prototype('integer')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('options')->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('base')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('redirect')->defaultNull()->end()
                            ->end()
                        ->end()
                        ->arrayNode('redis')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('prefix')->defaultValue('socloz_feature_flag')->end()
                                ->scalarNode('host')->defaultValue('localhost')->end()
                            ->end()
                        ->end()
                        ->arrayNode('google_analytics')->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('variable_id')->defaultValue(1)->end()
                                ->scalarNode('variable_name')->defaultValue("socloz_feature_flag_variation")->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('features')
                    ->useAttributeAsKey('name')
                    ->prototype('array')
                    ->children()
                        ->scalarNode('state')
                            ->isRequired()
                            ->validate()
                            ->ifNotInArray(array('enabled', 'enabled-always', 'disabled', 'disabled-hidden', 'disabled-always'))
                                ->thenInvalid('Invalid state "%s"')
                            ->end()
                        ->end()
                        ->scalarNode('description')->defaultNull()->end()
                        ->variableNode('variations')->defaultNull()->end()
                    ->end()
                ->end();

        return $treeBuilder;
    }
}
