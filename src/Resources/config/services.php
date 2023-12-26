<?php

declare(strict_types=1);

use FD\SymfonyLogViewerBundle\Controller\FoldersController;
use FD\SymfonyLogViewerBundle\Controller\IndexController;
use FD\SymfonyLogViewerBundle\Controller\LogController;
use FD\SymfonyLogViewerBundle\Routing\RouteLoader;
use FD\SymfonyLogViewerBundle\Service\FinderService;
use FD\SymfonyLogViewerBundle\Service\JsonManifestVersionStrategy;
use FD\SymfonyLogViewerBundle\Service\LogFileService;
use FD\SymfonyLogViewerBundle\Service\LogFolderFactory;
use FD\SymfonyLogViewerBundle\Service\LogFolderOutputFactory;
use FD\SymfonyLogViewerBundle\Service\LogFolderOutputProvider;
use FD\SymfonyLogViewerBundle\Service\LogFolderOutputSorter;
use FD\SymfonyLogViewerBundle\Service\LogParser;
use FD\SymfonyLogViewerBundle\Service\PerformanceService;
use FD\SymfonyLogViewerBundle\Service\StreamReaderFactory;
use FD\SymfonyLogViewerBundle\Service\VersionService;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(IndexController::class)->tag('controller.service_arguments');
    $services->set(FoldersController::class)->tag('controller.service_arguments');
    $services->set(LogController::class)
        ->arg('$loggerLocator', tagged_iterator('fd.symfony.log.viewer.logger'))
        ->tag('controller.service_arguments');

    $services->set(RouteLoader::class)
        ->tag('routing.loader');

    $services->set(JsonManifestVersionStrategy::class)
        ->arg('$manifestPath', '%kernel.project_dir%/public/bundles/symfonylogviewer/manifest.json');

    $services->set(FinderService::class)->arg('$logPath', '%kernel.logs_dir%');
    $services->set(LogFileService::class);
    $services->set(LogFolderFactory::class);
    $services->set(LogFolderOutputFactory::class);
    $services->set(LogFolderOutputProvider::class);
    $services->set(LogFolderOutputSorter::class);
    $services->set(LogParser::class);
    $services->set(StreamReaderFactory::class);
    $services->set(PerformanceService::class);
    $services->set(VersionService::class);
};
