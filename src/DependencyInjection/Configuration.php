<?php

declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
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
            ->arrayNode('log_files')
                ->info('List of log files to show')
                ->useAttributeAsKey('log_name')
                ->arrayPrototype()
                    ->children()
                    ->scalarNode('type')
                        ->info("The type of log file: monolog, nginx, apache, or the service id of an implementation of `LogFileParserInterface`")
                    ->end()
                    ->scalarNode('name')->info("The pretty name to show for these log files")->defaultNull()->end()
                    ->scalarNode('path')->info("The symfony/finder pattern to search for log files. Example: %kernel.logs_dir%/*.log")->end()
                    ->scalarNode('downloadable')->info("Whether or not to allow downloading of the log file")->defaultFalse()->end()
                    ->scalarNode('start_of_line_pattern')
                        ->info('The regex pattern for the start of a log line. Adds support for multiline log messages.')
                        ->defaultNull()
                    ->end()
                    ->scalarNode('log_message_pattern')
                        ->info('The regex pattern for a full log message which could include newlines.')
                    ->end()
                    ->scalarNode('date_format')->info('The date format of how to present the date in the frontend.')->defaultNull()->end()
                    ->arrayNode('levels')->info('Log level spelling. Key-value pair. Case sensitive')->end()
                    ->arrayNode('channels')->info('Log channel spelling. Key-value pair. Case sensitive')->end()
                ->end()
            ->end();

        return $tree;
    }
}
