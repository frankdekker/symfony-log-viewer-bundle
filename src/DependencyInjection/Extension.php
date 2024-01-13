<?php
declare(strict_types=1);

namespace FD\LogViewer\DependencyInjection;

use FD\LogViewer\Entity\Config\FinderConfig;
use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Service\JsonManifestVersionStrategy;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension as BaseExtension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Throwable;

/**
 * @codeCoverageIgnore
 * @internal
 */
final class Extension extends BaseExtension implements PrependExtensionInterface
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
                ->setArgument('$logMessagePattern', $config['log_message_pattern'])
                ->setArgument('$dateFormat', $config['date_format'] ?? "Y-m-d H:i:s");
        }
    }

    public function getAlias(): string
    {
        return 'fd_log_viewer';
    }

    /**
     * @inheritdoc
     */
    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig(
            'framework',
            [
                'assets' => [
                    'enabled' => true,
                    'packages' => [
                        'fd_symfony_log_viewer' => [
                            'version_strategy' => JsonManifestVersionStrategy::class
                        ],
                    ],
                ],
            ]
        );
    }
}
