<?php
/**
 * (Symfony 2) Bundle Configuration
 *
 * @copyright 2013 Anthon Pang
 * @license BSD-2-Clause
 */

namespace VIPSoft\CodeCoverageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Validate and merge configuration
 *
 * @author Anthon Pang <apang@softwaredevelopment.ca>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('vipsoft_codecoverage');

        $rootNode
            ->children()
                ->scalarNode('default')
                    ->defaultValue('sqlite')
                ->end()
                ->arrayNode('sqlite')
                    ->children()
                        ->scalarNode('database')
                            ->defaultValue('code_coverage.dbf')
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
