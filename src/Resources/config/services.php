<?php

declare(strict_types=1);

use FD\LogViewer\Controller\DeleteFileController;
use FD\LogViewer\Controller\DeleteFolderController;
use FD\LogViewer\Controller\DownloadFileController;
use FD\LogViewer\Controller\DownloadFolderController;
use FD\LogViewer\Controller\FoldersController;
use FD\LogViewer\Controller\IndexController;
use FD\LogViewer\Controller\LogRecordsController;
use FD\LogViewer\Reader\Stream\StreamReaderFactory;
use FD\LogViewer\Routing\RouteLoader;
use FD\LogViewer\Routing\RouteService;
use FD\LogViewer\Service\File\Apache\ApacheErrorFileParser;
use FD\LogViewer\Service\File\Http\HttpAccessFileParser;
use FD\LogViewer\Service\File\LogFileParserProvider;
use FD\LogViewer\Service\File\LogFileService;
use FD\LogViewer\Service\File\LogParser;
use FD\LogViewer\Service\File\LogQueryDtoFactory;
use FD\LogViewer\Service\File\LogRecordsOutputProvider;
use FD\LogViewer\Service\File\Monolog\MonologFileParser;
use FD\LogViewer\Service\File\Nginx\NginxErrorFileParser;
use FD\LogViewer\Service\FinderFactory;
use FD\LogViewer\Service\Folder\LogFolderFactory;
use FD\LogViewer\Service\Folder\LogFolderOutputFactory;
use FD\LogViewer\Service\Folder\LogFolderOutputProvider;
use FD\LogViewer\Service\Folder\LogFolderOutputSorter;
use FD\LogViewer\Service\Folder\ZipArchiveFactory;
use FD\LogViewer\Service\JsonManifestAssetLoader;
use FD\LogViewer\Service\Matcher\ChannelTermMatcher;
use FD\LogViewer\Service\Matcher\DateAfterTermMatcher;
use FD\LogViewer\Service\Matcher\DateBeforeTermMatcher;
use FD\LogViewer\Service\Matcher\LogRecordMatcher;
use FD\LogViewer\Service\Matcher\SeverityTermMatcher;
use FD\LogViewer\Service\Matcher\WordTermMatcher;
use FD\LogViewer\Service\Parser\DateParser;
use FD\LogViewer\Service\Parser\ExpressionParser;
use FD\LogViewer\Service\Parser\QuotedStringParser;
use FD\LogViewer\Service\Parser\StringParser;
use FD\LogViewer\Service\Parser\TermParser;
use FD\LogViewer\Service\Parser\WordParser;
use FD\LogViewer\Service\PerformanceService;
use FD\LogViewer\Service\VersionService;
use FD\LogViewer\Util\Clock;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\inline_service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();
    $services
        ->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(IndexController::class)
        ->arg('$homeRoute', '%fd.symfony.log.viewer.log_files_config.home_route%')
        ->tag('controller.service_arguments');
    $services->set(FoldersController::class)->tag('controller.service_arguments');
    $services->set(LogRecordsController::class)->tag('controller.service_arguments');
    $services->set(DownloadFileController::class)->tag('controller.service_arguments');
    $services->set(DownloadFolderController::class)->tag('controller.service_arguments');
    $services->set(DeleteFileController::class)->tag('controller.service_arguments');
    $services->set(DeleteFolderController::class)->tag('controller.service_arguments');

    $services->set(RouteService::class);
    $services->set(RouteLoader::class)
        ->tag('routing.loader');

    $services->set(JsonManifestAssetLoader::class)
        ->arg('$manifestPath', '%kernel.project_dir%/public/bundles/fdlogviewer/.vite/manifest.json');

    $services->set(ExpressionParser::class)
        ->arg(
            '$termParser',
            inline_service(TermParser::class)
                ->arg(
                    '$stringParser',
                    inline_service(StringParser::class)
                        ->arg('$quotedStringParser', inline_service(QuotedStringParser::class))
                        ->arg('$wordParser', inline_service(WordParser::class))
                )
                ->arg('$dateParser', inline_service(DateParser::class))
        );

    $services->set(FinderFactory::class);
    $services->set(LogFileService::class)->arg('$logFileConfigs', tagged_iterator('fd.symfony.log.viewer.log_files_config'));
    $services->set(LogFolderFactory::class);
    $services->set(LogFolderOutputFactory::class);
    $services->set(LogFolderOutputProvider::class);
    $services->set(LogFolderOutputSorter::class);
    $services->set(LogRecordsOutputProvider::class);
    $services->set(LogParser::class)->arg('$clock', inline_service(Clock::class));
    $services->set(LogFileParserProvider::class)
        ->arg('$logParsers', tagged_iterator('fd.symfony.log.viewer.log_file_parser', 'name'));
    $services->set(LogQueryDtoFactory::class);
    $services->set(MonologFileParser::class)->tag('fd.symfony.log.viewer.log_file_parser', ['name' => 'monolog'])
        ->arg('$loggerLocator', tagged_iterator('fd.symfony.log.viewer.logger'));
    $services->set(HttpAccessFileParser::class)->tag('fd.symfony.log.viewer.log_file_parser', ['name' => 'http-access']);
    $services->set(NginxErrorFileParser::class)->tag('fd.symfony.log.viewer.log_file_parser', ['name' => 'nginx-error']);
    $services->set(ApacheErrorFileParser::class)->tag('fd.symfony.log.viewer.log_file_parser', ['name' => 'apache-error']);
    $services->set(PerformanceService::class);
    $services->set(StreamReaderFactory::class);
    $services->set(VersionService::class);
    $services->set(ZipArchiveFactory::class);

    $services->set(DateBeforeTermMatcher::class)->tag('fd.symfony.log.viewer.term_matcher');
    $services->set(DateAfterTermMatcher::class)->tag('fd.symfony.log.viewer.term_matcher');
    $services->set(SeverityTermMatcher::class)->tag('fd.symfony.log.viewer.term_matcher');
    $services->set(ChannelTermMatcher::class)->tag('fd.symfony.log.viewer.term_matcher');
    $services->set(WordTermMatcher::class)->tag('fd.symfony.log.viewer.term_matcher');
    $services->set(LogRecordMatcher::class)->arg('$termMatchers', tagged_iterator('fd.symfony.log.viewer.term_matcher'));
};
