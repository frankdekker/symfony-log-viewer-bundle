<?php

declare(strict_types=1);

use FD\SymfonyLogViewerBundle\Controller\IndexController;
use FD\SymfonyLogViewerBundle\Routing\RouteLoader;
use FD\SymfonyLogViewerBundle\Service\JsonManifestVersionStrategy;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set(IndexController::class)
        ->arg('$twig', service('twig'))
        ->tag('controller.service_arguments');

    $services->set(RouteLoader::class)
        ->arg('$kernel', service('kernel'))
        ->tag('routing.loader');

    $services->set(JsonManifestVersionStrategy::class)
        ->arg('$manifestPath', '%kernel.project_dir%/public/log-viewer/manifest.json');
};
