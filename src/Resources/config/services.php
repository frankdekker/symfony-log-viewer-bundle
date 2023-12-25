<?php

declare(strict_types=1);

use FD\SymfonyLogViewerBundle\Controller\IndexController;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services();

    $services->set(IndexController::class)->tag('controller.service_arguments');
};
