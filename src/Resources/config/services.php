<?php

declare(strict_types=1);

use FD\SymfonyLogViewerBundle\Controller\FoldersController;
use FD\SymfonyLogViewerBundle\Controller\IndexController;
use FD\SymfonyLogViewerBundle\Controller\LogRecordsController;
use FD\SymfonyLogViewerBundle\Routing\RouteLoader;
use FD\SymfonyLogViewerBundle\Routing\RouteService;
use FD\SymfonyLogViewerBundle\Service\File\LogFileService;
use FD\SymfonyLogViewerBundle\Service\File\LogParser;
use FD\SymfonyLogViewerBundle\Service\File\LogQueryDtoFactory;
use FD\SymfonyLogViewerBundle\Service\File\Monolog\MonologFileParser;
use FD\SymfonyLogViewerBundle\Service\FinderService;
use FD\SymfonyLogViewerBundle\Service\Folder\LogFolderFactory;
use FD\SymfonyLogViewerBundle\Service\Folder\LogFolderOutputFactory;
use FD\SymfonyLogViewerBundle\Service\Folder\LogFolderOutputProvider;
use FD\SymfonyLogViewerBundle\Service\Folder\LogFolderOutputSorter;
use FD\SymfonyLogViewerBundle\Service\JsonManifestVersionStrategy;
use FD\SymfonyLogViewerBundle\Service\PerformanceService;
use FD\SymfonyLogViewerBundle\Service\VersionService;
use FD\SymfonyLogViewerBundle\StreamReader\StreamReaderFactory;
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
    $services->set(LogRecordsController::class)->tag('controller.service_arguments');

    $services->set(RouteService::class);
    $services->set(RouteLoader::class)
        ->tag('routing.loader');

    $services->set(JsonManifestVersionStrategy::class)
        ->arg('$manifestPath', '%kernel.project_dir%/public/bundles/symfonylogviewer/.vite/manifest.json');

    $services->set(FinderService::class);
    $services->set(LogFileService::class)->arg('$logFileConfigs', tagged_iterator('fd.symfony.log.viewer.log_files_config'));
    $services->set(LogFolderFactory::class);
    $services->set(LogFolderOutputFactory::class);
    $services->set(LogFolderOutputProvider::class);
    $services->set(LogFolderOutputSorter::class);
    $services->set(LogParser::class);
    $services->set(LogQueryDtoFactory::class);
    $services->set(MonologFileParser::class)->arg('$loggerLocator', tagged_iterator('fd.symfony.log.viewer.logger'));
    $services->set(PerformanceService::class);
    $services->set(StreamReaderFactory::class);
    $services->set(VersionService::class);
};
