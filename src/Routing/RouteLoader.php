<?php
declare(strict_types=1);

namespace FD\LogViewer\Routing;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Loader\PhpFileLoader;
use Symfony\Component\Routing\RouteCollection;

class RouteLoader extends Loader
{
    /**
     * @inheritDoc
     */
    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        $fileLocator = new FileLocator(dirname(__DIR__) . '/Resources/config');
        $fileLoader  = new PhpFileLoader($fileLocator);

        $routeCollection = new RouteCollection();
        $routeCollection->addCollection($fileLoader->load('routes.php'));

        return $routeCollection;
    }

    /**
     * @inheritDoc
     */
    public function supports(mixed $resource, ?string $type = null): bool
    {
        return $type === 'fd_symfony_log_viewer';
    }
}
