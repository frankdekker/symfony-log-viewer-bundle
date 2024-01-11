<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Routing;

use FD\LogViewer\Controller\IndexController;
use FD\LogViewer\Routing\RouteService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

#[CoversClass(RouteService::class)]
class RouteServiceTest extends TestCase
{
    private RouterInterface&MockObject $router;
    private RouteService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->router  = $this->createMock(RouterInterface::class);
        $this->service = new RouteService($this->router);
    }

    public function testGetBaseUriFailure(): void
    {
        $routeCollection = $this->createMock(RouteCollection::class);
        $routeCollection->expects(self::once())->method('get')->with(IndexController::class . '.base')->willReturn(null);

        $this->router->expects(self::once())->method('getRouteCollection')->willReturn($routeCollection);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to determine baseUri from IndexController route');
        $this->service->getBaseUri();
    }

    public function testGetBaseUriSuccess(): void
    {
        $route = new Route('/path/');

        $routeCollection = $this->createMock(RouteCollection::class);
        $routeCollection->expects(self::once())->method('get')->with(IndexController::class . '.base')->willReturn($route);

        $this->router->expects(self::once())->method('getRouteCollection')->willReturn($routeCollection);

        static::assertSame('/path/', $this->service->getBaseUri());
    }
}
