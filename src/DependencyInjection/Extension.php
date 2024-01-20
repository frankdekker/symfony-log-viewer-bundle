<?php
declare(strict_types=1);

namespace FD\LogViewer\DependencyInjection;

use FD\LogViewer\Entity\Config\FinderConfig;
use FD\LogViewer\Entity\Config\LogFilesConfig;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension as BaseExtension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Throwable;

/**
 * @codeCoverageIgnore
 * @internal
 */
final class Extension extends BaseExtension
{
    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        $mergedConfigs = $this->processConfiguration(new Configuration(), $configs);

        // add defaults
        if ($mergedConfigs['enable_default_monolog']) {
            $mergedConfigs = self::addMonologDefault($mergedConfigs);
        }

        foreach ($mergedConfigs['log_files'] as $key => $config) {
            $container->register('fd.symfony.log.viewer.log_files_config.finder.' . $key, FinderConfig::class)
                ->setPublic(false)
                ->setArgument('$inDirectories', $config['finder']['in'])
                ->setArgument('$fileName', $config['finder']['name'])
                ->setArgument('$ignoreUnreadableDirs', $config['finder']['ignoreUnreadableDirs'])
                ->setArgument('$followLinks', $config['finder']['followLinks']);

            $container->register('fd.symfony.log.viewer.log_files_config.config.' . $key, LogFilesConfig::class)
                ->addTag('fd.symfony.log.viewer.log_files_config')
                ->setPublic(false)
                ->setArgument('$logName', $key)
                ->setArgument('$type', $config['type'])
                ->setArgument('$name', $config['name'] ?? null)
                ->setArgument('$finderConfig', new Reference('fd.symfony.log.viewer.log_files_config.finder.' . $key))
                ->setArgument('$downloadable', $config['downloadable'])
                ->setArgument('$deletable', $config['deletable'])
                ->setArgument('$startOfLinePattern', $config['start_of_line_pattern'] ?? null)
                ->setArgument('$logMessagePattern', $config['log_message_pattern'] ?? null)
                ->setArgument('$dateFormat', $config['date_format'] ?? "Y-m-d H:i:s");
        }
    }

    public function getAlias(): string
    {
        return 'fd_log_viewer';
    }

    /**
     * @template T of array
     * @phpstan-param T $configs
     *
     * @phpstan-return T
     */
    private static function addMonologDefault(array $configs): array
    {
        // monolog
        $configs['log_files']['monolog']['type']         ??= 'monolog';
        $configs['log_files']['monolog']['name']         ??= 'Monolog';
        $configs['log_files']['monolog']['downloadable'] ??= false;
        $configs['log_files']['monolog']['deletable']    ??= false;

        // finder
        $configs['log_files']['monolog']['finder']['in']                   ??= '%kernel.logs_dir%';
        $configs['log_files']['monolog']['finder']['name']                 ??= '*.log';
        $configs['log_files']['monolog']['finder']['ignoreUnreadableDirs'] ??= true;
        $configs['log_files']['monolog']['finder']['followLinks']          ??= false;

        return $configs;
    }
}
