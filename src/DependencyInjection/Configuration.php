<?php

declare(strict_types=1);

namespace FD\LogViewer\DependencyInjection;

use Closure;
use FD\LogViewer\Service\File\Monolog\MonologLineParser;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @internal
 */
final class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder<'array'>
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $tree     = new TreeBuilder('fd_log_viewer');
        $rootNode = $tree->getRootNode();

        $rootNode
            ->children()
            ->scalarNode('home_route')->info("The name of the route to redirect to when clicking the back button")->end()
            ->scalarNode('show_performance_details')
                ->defaultTrue()
                ->info("Will toggle if the performance information and version will be shown. Default true")
            ->end()
            ->append($this->configureLogFiles())
            ->append($this->configureHosts());

        return $tree;
    }

    /**
     * @return ArrayNodeDefinition<null>
     */
    private function configureLogFiles(): ArrayNodeDefinition
    {
        $tree     = new TreeBuilder('log_files');
        $rootNode = $tree->getRootNode();

        return $rootNode
            ->info('List of log files to show')
            ->useAttributeAsKey('log_name')
            ->requiresAtLeastOneElement()
            ->beforeNormalization()
                ->always()
                ->then(Closure::fromCallable([$this, 'normalizeConfig']))
            ->end()
            ->defaultValue(
                [
                    'monolog' => [
                        'type'                  => 'monolog',
                        'name'                  => 'Monolog',
                        'finder'                => [
                            'in'                   => '%kernel.logs_dir%',
                            'name'                 => '*.log',
                            'depth'                => '== 0',
                            'ignoreUnreadableDirs' => true,
                            'followLinks'          => false,
                        ],
                        'downloadable'          => false,
                        'deletable'             => false,
                        'start_of_line_pattern' => MonologLineParser::START_OF_MESSAGE_PATTERN,
                        'log_message_pattern'   => MonologLineParser::LOG_LINE_PATTERN,
                        'date_format'           => null,
                    ]
                ]
            )
            ->arrayPrototype()
                ->children()
                    ->scalarNode('type')
                        ->info("The type of log file: monolog, nginx, apache, or the service id of an implementation of `LogFileParserInterface`")
                    ->end()
                    ->scalarNode('name')->info("The pretty name to show for these log files")->end()
                    ->arrayNode('finder')
                        ->isRequired()
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
                    ->arrayNode('open')
                        ->children()
                            ->scalarNode('pattern')->isRequired()->info('A pattern to match a log file. Use * as wildcard. Example: dev-*.log')->end()
                            ->scalarNode('order')
                                ->info('Either "newest" or "oldest"')
                                ->defaultValue('newest')
                                ->validate()
                                    ->ifNotInArray(['newest', 'oldest'])
                                    ->thenInvalid('The order must either be `newest` or `oldest.`')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                    ->scalarNode('downloadable')->info("Whether or not to allow downloading of the log file")->defaultFalse()->end()
                    ->scalarNode('deletable')->info("Whether or not to allow deletion of the log file")->defaultFalse()->end()
                    ->scalarNode('start_of_line_pattern')
                        ->info('The regex pattern for the start of a log line. Adds support for multiline log messages.')
                        ->defaultNull()
                    ->end()
                    ->scalarNode('log_message_pattern')
                        ->info('The regex pattern for a full log message which could include newlines.')
                        ->defaultNull()
                    ->end()
                    ->scalarNode('date_format')
                        ->info('The date format to parse the date from the log entry. If set to `null`, the format will be guessed by `strtotime`')
                        ->defaultNull()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @return ArrayNodeDefinition<null>
     */
    private function configureHosts(): ArrayNodeDefinition
    {
        $tree     = new TreeBuilder('hosts');
        $rootNode = $tree->getRootNode();

        return $rootNode
            ->info('List of hosts')
            ->useAttributeAsKey('host_name')
            ->requiresAtLeastOneElement()
            ->defaultValue(['localhost' => ['name' => 'Local', 'host' => null]])
            ->arrayPrototype()
                ->children()
                    ->scalarNode('name')->info("The pretty name to show for this host")->end()
                    ->scalarNode('host')->info("The host to connect to")->defaultNull()->end()
                    ->arrayNode('auth')
                        ->children()
                            ->scalarNode('type')
                                ->info('An implementation of AuthenticatorInterface')
                                ->isRequired()
                            ->end()
                            ->arrayNode('options')
                                ->info('The options to pass to the authenticator, see the authenticator for the available options')
                                ->requiresAtLeastOneElement()
                                ->useAttributeAsKey('name')
                                ->prototype('scalar')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
    }

    /**
     * @param array{monolog?: array{type?: string, name?: string, finder?: array{in?: string, name?: string, depth?: string}}} $config
     *
     * @return array{monolog?: array{type: string, name: string, finder: array{in: string, name: string, depth: string}}}
     */
    private function normalizeConfig(array $config): array
    {
        // default monolog config is overridden
        if (count($config) !== 0 && array_key_exists('monolog', $config) === false) {
            return $config;
        }

        $config['monolog']['type']                  ??= 'monolog';
        $config['monolog']['name']                  ??= 'Monolog';
        $config['monolog']['finder']['in']          ??= '%kernel.logs_dir%';
        $config['monolog']['finder']['name']        ??= '*.log';
        $config['monolog']['finder']['depth']       ??= '== 0';
        $config['monolog']['start_of_line_pattern'] ??= MonologLineParser::START_OF_MESSAGE_PATTERN;
        $config['monolog']['log_message_pattern']   ??= MonologLineParser::LOG_LINE_PATTERN;

        return $config;
    }
}
