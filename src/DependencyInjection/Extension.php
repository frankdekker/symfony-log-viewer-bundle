<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\DependencyInjection;

use FD\SymfonyLogViewerBundle\Routing\RouteLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

final class Extension extends ConfigurableExtension
{
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $container->register(RouteLoader::class)
            ->setArguments([new Reference('kernel')])
            ->addTag('routing.loader');
    }
}
