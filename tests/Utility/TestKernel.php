<?php

declare(strict_types=1);

namespace FD\LogViewer\Tests\Utility;

use Exception;
use FD\LogViewer\FDLogViewerBundle;
use FD\LogViewer\Service\JsonManifestAssetLoader;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class TestKernel extends BaseKernel implements CompilerPassInterface
{
    /**
     * @return iterable<BundleInterface>
     */
    public function registerBundles(): iterable
    {
        return [new FrameworkBundle(), new MonologBundle(), new TwigBundle(), new FDLogViewerBundle()];
    }

    public function getCacheDir(): string
    {
        return dirname(__DIR__) . '/.kernel/cache';
    }

    public function getLogDir(): string
    {
        return dirname(__DIR__) . '/resources/Functional/log';
    }

    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container): void
    {
        $container->getDefinition(JsonManifestAssetLoader::class)->setPublic(true);
    }

    /**
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension(
                'framework',
                [
                    'secret'               => 'test',
                    'test'                 => true,
                    'http_method_override' => false,
                    'php_errors'           => ['log' => true],
                    'profiler'             => ['enabled' => false],
                    'validation'           => ['email_validation_mode' => 'html5'],
                    'router'               => [
                        'resource'            => dirname(__DIR__) . '/resources/Functional/routes.php',
                        'type'                => 'php',
                        'strict_requirements' => true,
                        'utf8'                => true,
                    ],
                ]
            );

            $container->loadFromExtension(
                'fd_log_viewer',
                [
                    'log_files' => [
                        'monolog' => [
                            'downloadable' => true,
                            'deletable'    => true,
                        ],
                    ],
                ]
            );
            $container->loadFromExtension('twig', ['strict_variables' => true, 'debug' => false]);

            $container->register(Filesystem::class)->setPublic(true);
        });
    }
}
