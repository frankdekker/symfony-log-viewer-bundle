<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Integration\Routing;

use Exception;
use FD\SymfonyLogViewerBundle\Routing\RouteLoader;
use FD\SymfonyLogViewerBundle\Routing\RouteService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\RouterInterface;

#[CoversClass(RouteService::class)]
class RouteServiceTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGetBaseUri(): void
    {
        $routes = (new RouteLoader())->load(null);

        $router = $this->createMock(RouterInterface::class);
        $router->expects(static::once())->method('getRouteCollection')->willReturn($routes);

        $routeService = new RouteService($router);
        static::assertSame('/', $routeService->getBaseUri());
    }
}
