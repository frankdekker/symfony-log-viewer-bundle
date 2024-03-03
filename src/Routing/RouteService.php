<?php
declare(strict_types=1);

namespace FD\LogViewer\Routing;

use FD\LogViewer\Controller\IndexController;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
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

    public function getRelativeUriFor(Request $request): ?string
    {
        $uri = (string)parse_url($request->getRequestUri(), PHP_URL_PATH);

        $uri = preg_replace('/^' . preg_quote($this->getBaseUri(), '/') . '/', '', $uri);

        return is_string($uri) ? $uri : null;
    }
}
