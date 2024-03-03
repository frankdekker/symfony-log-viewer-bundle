<?php

declare(strict_types=1);

use FD\LogViewer\Controller\DeleteFileController;
use FD\LogViewer\Controller\DeleteFolderController;
use FD\LogViewer\Controller\DownloadFileController;
use FD\LogViewer\Controller\DownloadFolderController;
use FD\LogViewer\Controller\FoldersController;
use FD\LogViewer\Controller\IndexController;
use FD\LogViewer\Controller\LogRecordsController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes
        ->add(IndexController::class, ['base' => '/', 'base.slug' => '/{slug}'])
        ->methods(['GET'])
        ->controller([IndexController::class, '__invoke']);

    $routes
        ->add(FoldersController::class, '/api/folders')
        ->methods(['GET'])
        ->controller([FoldersController::class, '__invoke']);

    $routes
        ->add(LogRecordsController::class, '/api/logs')
        ->methods(['GET'])
        ->controller([LogRecordsController::class, '__invoke']);

    $routes
        ->add(DownloadFolderController::class, '/api/folder/{identifier}')
        ->methods(['GET'])
        ->controller([DownloadFolderController::class, '__invoke']);

    $routes
        ->add(DownloadFileController::class, '/api/file/{identifier}')
        ->methods(['GET'])
        ->controller([DownloadFileController::class, '__invoke']);

    $routes
        ->add(DeleteFileController::class, '/api/file/{identifier}')
        ->methods(['DELETE'])
        ->controller([DeleteFileController::class, '__invoke']);

    $routes
        ->add(DeleteFolderController::class, '/api/folder/{identifier}')
        ->methods(['DELETE'])
        ->controller([DeleteFolderController::class, '__invoke']);
};
