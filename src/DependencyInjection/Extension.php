<?php
declare(strict_types=1);

namespace FD\LogViewer\DependencyInjection;

use FD\LogViewer\Entity\Config\FinderConfig;
use FD\LogViewer\Entity\Config\HostAuthenticationConfig;
use FD\LogViewer\Entity\Config\HostConfig;
use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\Config\OpenFileConfig;
use FD\LogViewer\Service\File\LogRecordsOutputProvider;
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

        $container->setParameter('fd.symfony.log.viewer.log_files_config.home_route', $mergedConfigs['home_route'] ?? null);

        if ($mergedConfigs['show_performance_details'] === false) {
            $container->getDefinition(LogRecordsOutputProvider::class)->setArgument('$performanceService', null);
        }

        foreach ($mergedConfigs['log_files'] as $key => $config) {
            $container->register('fd.symfony.log.viewer.log_files_config.finder.' . $key, FinderConfig::class)
                ->setPublic(false)
                ->setArgument('$inDirectories', $config['finder']['in'])
                ->setArgument('$fileName', $config['finder']['name'])
                ->setArgument('$depth', $config['finder']['depth'])
                ->setArgument('$ignoreUnreadableDirs', $config['finder']['ignoreUnreadableDirs'])
                ->setArgument('$followLinks', $config['finder']['followLinks']);

            $openFileConfigReference = null;
            if (isset($config['open'])) {
                $container->register('fd.symfony.log.viewer.log_files_config.open.' . $key, OpenFileConfig::class)
                    ->setPublic(false)
                    ->setArgument('$pattern', $config['open']['pattern'])
                    ->setArgument('$order', $config['open']['order']);
                $openFileConfigReference = new Reference('fd.symfony.log.viewer.log_files_config.open.' . $key);
            }

            $container->register('fd.symfony.log.viewer.log_files_config.config.' . $key, LogFilesConfig::class)
                ->addTag('fd.symfony.log.viewer.log_files_config')
                ->setPublic(false)
                ->setArgument('$logName', $key)
                ->setArgument('$type', $config['type'])
                ->setArgument('$name', $config['name'])
                ->setArgument('$finderConfig', new Reference('fd.symfony.log.viewer.log_files_config.finder.' . $key))
                ->setArgument('$open', $openFileConfigReference)
                ->setArgument('$downloadable', $config['downloadable'])
                ->setArgument('$deletable', $config['deletable'])
                ->setArgument('$startOfLinePattern', $config['start_of_line_pattern'])
                ->setArgument('$logMessagePattern', $config['log_message_pattern'])
                ->setArgument('$dateFormat', $config['date_format']);
        }

        foreach ($mergedConfigs['hosts'] as $key => $config) {
            if (isset($config['auth'])) {
                $container->register('fd.symfony.log.viewer.hosts_config.authentication.' . $key, HostAuthenticationConfig::class)
                    ->setPublic(false)
                    ->setArgument('$type', $config['auth']['type'])
                    ->setArgument('$options', $config['auth']['options'] ?? []);
            }

            $container->register('fd.symfony.log.viewer.hosts_config.config.' . $key, HostConfig::class)
                ->addTag('fd.symfony.log.viewer.hosts_config')
                ->setPublic(false)
                ->setArgument('$key', $key)
                ->setArgument('$name', $config['name'])
                ->setArgument('$host', $config['host'])
                ->setArgument(
                    '$authentication',
                    new Reference('fd.symfony.log.viewer.hosts_config.authentication.' . $key, ContainerBuilder::NULL_ON_INVALID_REFERENCE)
                );
        }
    }

    public function getAlias(): string
    {
        return 'fd_log_viewer';
    }
}
