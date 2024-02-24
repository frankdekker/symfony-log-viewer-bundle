<?php

declare(strict_types=1);

namespace FD\LogViewer\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @internal
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $tree = new TreeBuilder('fd_log_viewer');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $tree->getRootNode();

        $rootNode->children()
            ->scalarNode('enable_default_monolog')
                ->info('Enable default monolog configuration')
                ->defaultTrue()
            ->end()
            ->scalarNode('home_route')
                ->info("The name of the route to redirect to when clicking the back button")
            ->end()
            ->append($this->configureLogFiles());

        return $tree;
    }

    private function configureLogFiles(): NodeDefinition
    {
        $tree = new TreeBuilder('log_files');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $tree->getRootNode();

        return $rootNode
            ->info('List of log files to show')
            ->useAttributeAsKey('log_name')
            ->requiresAtLeastOneElement()
            ->arrayPrototype()
                ->children()
                    ->scalarNode('type')
                        ->info("The type of log file: monolog, nginx, apache, or the service id of an implementation of `LogFileParserInterface`")
                    ->end()
                    ->scalarNode('name')->info("The pretty name to show for these log files")->end()
                    ->arrayNode('finder')
                        ->children()
                            ->scalarNode('in')
                                ->info("The symfony/finder pattern to iterate through directories. Example: %kernel.logs_dir%")
                            ->end()
                            ->scalarNode('name')
                                ->info("The symfony/finder pattern to filter files. Example: *.log")
                                ->defaultNull()
                            ->end()
                            ->scalarNode('depth')
                                ->info("The symfony/finder directory depth to search files for. Example: '> 0'")
                                ->defaultNull()
                            ->end()
                            ->scalarNode('ignoreUnreadableDirs')
                                ->info("Whether to ignore unreadable directories")
                                ->defaultTrue()
                            ->end()
                            ->scalarNode('followLinks')
                                ->info("Whether to follow symlinks")
                                ->defaultFalse()
                            ->end()
                        ->end()
                    ->end()
                    ->scalarNode('downloadable')->info("Whether or not to allow downloading of the log file")->defaultFalse()->end()
                    ->scalarNode('deletable')->info("Whether or not to allow deletion of the log file")->defaultFalse()->end()
                    ->scalarNode('start_of_line_pattern')
                        ->info('The regex pattern for the start of a log line. Adds support for multiline log messages.')
                        ->defaultNull()
                    ->end()
                    ->scalarNode('log_message_pattern')->info('The regex pattern for a full log message which could include newlines.')->end()
                    ->scalarNode('date_format')->info('The date format of how to present the date in the frontend.')->defaultNull()->end()
                ->end()
            ->end();
    }

    private function configureHosts(): NodeDefinition
    {
        $tree = new TreeBuilder('log_files');
        /** @var ArrayNodeDefinition $rootNode */
        $rootNode = $tree->getRootNode();

        return $rootNode
            ->info('List of hosts')
            ->useAttributeAsKey('host_name')
            ->arrayPrototype()
                ->children()
                    ->scalarNode('name')->info("The pretty name to show for this host")->end()
                    ->scalarNode('host')->info("The host to connect to")->defaultNull()->end()
                ->end()
            ->end();
    }
}
