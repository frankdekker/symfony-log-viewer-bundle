<?php

declare(strict_types=1);

namespace FD\LogViewer\Tests\Utility;

use Exception;
use FD\LogViewer\FdLogViewerBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class TestKernel extends BaseKernel
{
    /**
     * @return iterable<BundleInterface>
     */
    public function registerBundles(): iterable
    {
        return [new FrameworkBundle(), new MonologBundle(), new TwigBundle(), new FdLogViewerBundle()];
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
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension(
                'framework',
                [
                    'secret'                => 'test',
                    'test'                  => null,
                    'http_method_override'  => false,
                    'handle_all_throwables' => true,
                    'php_errors'            => ['log' => true],
                    'profiler'              => ['enabled' => false],
                    'validation'            => ['email_validation_mode' => 'html5'],
                    'router'                => [
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
        });
    }
}
