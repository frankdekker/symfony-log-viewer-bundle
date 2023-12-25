<?php

declare(strict_types=1);

use FD\SymfonyLogViewerBundle\Controller\FoldersController;
use FD\SymfonyLogViewerBundle\Controller\IndexController;
use FD\SymfonyLogViewerBundle\Controller\LogController;
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
        ->add(LogController::class, '/api/logs')
        ->methods(['GET'])
        ->controller([LogController::class, '__invoke']);
};
