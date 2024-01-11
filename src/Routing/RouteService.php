<?php
declare(strict_types=1);

namespace FD\LogViewer\Routing;

use FD\LogViewer\Controller\IndexController;
use RuntimeException;
use Symfony\Component\Routing\RouterInterface;

class RouteService
{
    public function __construct(private readonly RouterInterface $router)
    {
    }

    public function getBaseUri(): string
    {
        // retrieve base uri from route
        $baseUri = $this->router->getRouteCollection()->get(IndexController::class . '.base')?->getPath();
        if ($baseUri === null) {
            throw new RuntimeException('Unable to determine baseUri from IndexController route');
        }

        return $baseUri;
    }
}
