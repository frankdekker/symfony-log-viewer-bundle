<?php

declare(strict_types=1);

use FD\LogViewer\Controller\DeleteFileRemoteHost;
use FD\LogViewer\Controller\DeleteFolderRemoteHost;
use FD\LogViewer\Controller\DownloadFileRemoteHost;
use FD\LogViewer\Controller\DownloadFolderRemoteHost;
use FD\LogViewer\Controller\FoldersRemoteHost;
use FD\LogViewer\Controller\IndexController;
use FD\LogViewer\Controller\LogRecordsRemoteHost;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes
        ->add(IndexController::class, ['base' => '/', 'base.slug' => '/{slug}'])
        ->methods(['GET'])
        ->controller([IndexController::class, '__invoke']);

    $routes
        ->add(FoldersRemoteHost::class, '/api/folders')
        ->methods(['GET'])
        ->controller([FoldersRemoteHost::class, '__invoke']);

    $routes
        ->add(LogRecordsRemoteHost::class, '/api/logs')
        ->methods(['GET'])
        ->controller([LogRecordsRemoteHost::class, '__invoke']);

    $routes
        ->add(DownloadFolderRemoteHost::class, '/api/folder/{identifier}')
        ->methods(['GET'])
        ->controller([DownloadFolderRemoteHost::class, '__invoke']);

    $routes
        ->add(DownloadFileRemoteHost::class, '/api/file/{identifier}')
        ->methods(['GET'])
        ->controller([DownloadFileRemoteHost::class, '__invoke']);

    $routes
        ->add(DeleteFileRemoteHost::class, '/api/file/{identifier}')
        ->methods(['DELETE'])
        ->controller([DeleteFileRemoteHost::class, '__invoke']);

    $routes
        ->add(DeleteFolderRemoteHost::class, '/api/folder/{identifier}')
        ->methods(['DELETE'])
        ->controller([DeleteFolderRemoteHost::class, '__invoke']);
};
