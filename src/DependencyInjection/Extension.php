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
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Throwable;

/**
 * @phpstan-type Options array{
 *     home_route: string|null,
 *     show_performance_details: bool,
 *     log_files: array<string, array{
 *         type: string,
 *         name: string,
 *         finder: array{
 *             in: string,
 *             name: string,
 *             depth: int,
 *             ignoreUnreadableDirs: bool,
 *             followLinks: bool,
 *         },
 *         open?: array{
 *             pattern: string,
 *             order: 'newest'|'oldest'
 *         },
 *         downloadable: bool,
 *         deletable: bool,
 *         start_of_line_pattern: string|null,
 *         log_message_pattern: string|null,
 *         date_format: string|null
 *     }>,
 *     hosts: array<string, array{
 *         name: string,
 *         host: string,
 *         auth: array{
 *             type: string,
 *             options: array<string, string>
 *         }
 *     }>
 * }
 * @codeCoverageIgnore - This is a configuration class, tested by the functional test
 * @internal
 */
final class Extension extends ConfigurableExtension
{
    /**
     * @phpstan-param Options $mergedConfig
     * @throws Throwable
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        $container->setParameter('fd.symfony.log.viewer.log_files_config.home_route', $mergedConfig['home_route'] ?? null);

        if ($mergedConfig['show_performance_details'] === false) {
            $container->getDefinition(LogRecordsOutputProvider::class)->setArgument('$performanceService', null);
        }

        foreach ($mergedConfig['log_files'] as $key => $config) {
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
                ->setArgument('$openFileConfig', $openFileConfigReference)
                ->setArgument('$downloadable', $config['downloadable'])
                ->setArgument('$deletable', $config['deletable'])
                ->setArgument('$startOfLinePattern', $config['start_of_line_pattern'])
                ->setArgument('$logMessagePattern', $config['log_message_pattern'])
                ->setArgument('$dateFormat', $config['date_format']);
        }

        foreach ($mergedConfig['hosts'] as $key => $config) {
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
