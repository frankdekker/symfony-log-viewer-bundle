<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\DependencyInjection;

use FD\SymfonyLogViewerBundle\Service\JsonManifestVersionStrategy;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension as BaseExtension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Throwable;

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

        $this->processConfiguration(new Configuration(), $configs);
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
