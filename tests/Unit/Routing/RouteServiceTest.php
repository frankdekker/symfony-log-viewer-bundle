<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Routing;

use FD\LogViewer\Controller\IndexController;
use FD\LogViewer\Routing\RouteService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\RouterInterface;

#[CoversClass(RouteService::class)]
class RouteServiceTest extends TestCase
{
    private RouterInterface&MockObject $router;
    private RouteCollection&MockObject $routeCollection;
    private RouteService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->routeCollection = $this->createMock(RouteCollection::class);
        $this->router          = $this->createMock(RouterInterface::class);
        $this->service         = new RouteService($this->router);
    }

    public function testGetBaseUriFailure(): void
    {
        $this->routeCollection->expects(self::once())->method('get')->with(IndexController::class . '.base')->willReturn(null);
        $this->router->expects(self::once())->method('getRouteCollection')->willReturn($this->routeCollection);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to determine baseUri from IndexController route');
        $this->service->getBaseUri();
    }

    public function testGetBaseUriSuccess(): void
    {
        $route = new Route('/path/');

        $this->routeCollection->expects(self::once())->method('get')->with(IndexController::class . '.base')->willReturn($route);
        $this->router->expects(self::once())->method('getRouteCollection')->willReturn($this->routeCollection);

        static::assertSame('/path/', $this->service->getBaseUri());
    }

    public function testGetRelativeUriFor(): void
    {
        $request = new Request(server: ['REQUEST_URI' => '/path/api/endpoint?foo=bar']);
        $route   = new Route('/path/');

        $this->routeCollection->expects(self::once())->method('get')->with(IndexController::class . '.base')->willReturn($route);
        $this->router->expects(self::once())->method('getRouteCollection')->willReturn($this->routeCollection);

        static::assertSame('api/endpoint', $this->service->getRelativeUriFor($request));
    }
}
