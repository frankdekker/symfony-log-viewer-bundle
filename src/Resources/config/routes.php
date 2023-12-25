<?php

declare(strict_types=1);

use FD\SymfonyLogViewerBundle\Controller\IndexController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes) {
    $routes
        ->add(IndexController::class, ['log-viewer', '/log-viewer/{slug}'])
        ->methods(['GET'])
        ->controller([IndexController::class, '__invoke']);
};
