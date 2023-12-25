<?php

declare(strict_types=1);

use FD\SymfonyLogViewerBundle\Controller\IndexController;
use FD\SymfonyLogViewerBundle\Routing\RouteLoader;
use FD\SymfonyLogViewerBundle\Service\FinderService;
use FD\SymfonyLogViewerBundle\Service\JsonManifestVersionStrategy;
use FD\SymfonyLogViewerBundle\Service\LogFileService;
use FD\SymfonyLogViewerBundle\Service\LogFolderFactory;
use FD\SymfonyLogViewerBundle\Service\LogFolderOutputFactory;
use FD\SymfonyLogViewerBundle\Service\LogFolderOutputProvider;
use FD\SymfonyLogViewerBundle\Service\LogFolderOutputSorter;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services
        ->defaults()
        ->autowire();

    $services->set(IndexController::class)
        ->tag('controller.service_arguments');

    $services->set(RouteLoader::class)
        ->tag('routing.loader');

    $services->set(JsonManifestVersionStrategy::class)
        ->arg('$manifestPath', '%kernel.project_dir%/public/log-viewer/manifest.json');

    $services->set(FinderService::class)->arg('$logPath', '%kernel.logs_dir%');
    $services->set(LogFileService::class);
    $services->set(LogFolderFactory::class);
    $services->set(LogFolderOutputFactory::class);
    $services->set(LogFolderOutputProvider::class);
    $services->set(LogFolderOutputSorter::class);
};
