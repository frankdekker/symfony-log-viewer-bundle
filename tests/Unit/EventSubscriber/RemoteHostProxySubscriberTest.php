<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\EventSubscriber;

use FD\LogViewer\Controller\FoldersRemoteHost;
use FD\LogViewer\Entity\Config\HostConfig;
use FD\LogViewer\EventSubscriber\RemoteHostProxySubscriber;
use FD\LogViewer\Routing\RouteService;
use FD\LogViewer\Service\Host\HostInvokeService;
use FD\LogViewer\Service\Host\HostProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Throwable;

#[CoversClass(RemoteHostProxySubscriber::class)]
class RemoteHostProxySubscriberTest extends TestCase
{
    private ControllerResolverInterface&MockObject $controllerResolver;
    private RouteService&MockObject $routeService;
    private HostProvider&MockObject $hostProvider;
    private HostInvokeService&MockObject $invokeService;
    private FoldersRemoteHost&MockObject $controller;
    private RemoteHostProxySubscriber $subscriber;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controllerResolver = $this->createMock(ControllerResolverInterface::class);
        $this->routeService       = $this->createMock(RouteService::class);
        $this->hostProvider       = $this->createMock(HostProvider::class);
        $this->invokeService      = $this->createMock(HostInvokeService::class);
        $this->controller         = $this->createMock(FoldersRemoteHost::class);
        $this->subscriber         = new RemoteHostProxySubscriber(
            $this->controllerResolver,
            $this->routeService,
            $this->hostProvider,
            $this->invokeService
        );
    }

    /**
     * @throws Throwable
     */
    public function testOnKernelRequestUnsupportedController(): void
    {
        $request = new Request(['host' => 'remote']);
        $event   = $this->createMock(RequestEvent::class);
        $event->method('getRequest')->willReturn($request);

        $this->controllerResolver->expects(self::once())->method('getController')->with($request)->willReturn(false);

        $this->subscriber->onKernelRequest($event);
    }

    /**
     * @throws Throwable
     */
    public function testOnKernelRequestAbsentHostQueryParam(): void
    {
        $request = new Request(['host' => 'remote']);
        $event   = $this->createMock(RequestEvent::class);
        $event->method('getRequest')->willReturn($request);

        $this->controllerResolver->expects(self::once())->method('getController')->with($request)->willReturn([$this->controller, '__invoke']);
        $this->hostProvider->expects(self::once())->method('getHostByKey')->with('remote')->willReturn(null);

        $this->subscriber->onKernelRequest($event);
    }

    /**
     * @throws Throwable
     */
    public function testOnKernelRequestUnknownRelativeUri(): void
    {
        $request = new Request(['host' => 'remote']);
        $event   = $this->createMock(RequestEvent::class);
        $event->method('getRequest')->willReturn($request);
        $host = new HostConfig('key', 'name', 'remote');

        $this->controllerResolver->expects(self::once())->method('getController')->with($request)->willReturn([$this->controller, '__invoke']);
        $this->hostProvider->expects(self::once())->method('getHostByKey')->with('remote')->willReturn($host);
        $this->routeService->expects(self::once())->method('getRelativeUriFor')->with($request)->willReturn(null);

        $this->subscriber->onKernelRequest($event);
    }

    /**
     * @throws Throwable
     */
    public function testOnKernelRequestSuccess(): void
    {
        $request = new Request(['host' => 'remote']);
        $host     = new HostConfig('key', 'name', 'remote');
        $response = new Response();
        $event   = $this->createMock(RequestEvent::class);
        $event->method('getRequest')->willReturn($request);
        $event->method('setResponse')->with($response);

        $this->controllerResolver->expects(self::once())->method('getController')->with($request)->willReturn([$this->controller, '__invoke']);
        $this->hostProvider->expects(self::once())->method('getHostByKey')->with('remote')->willReturn($host);
        $this->routeService->expects(self::once())->method('getRelativeUriFor')->with($request)->willReturn('/api/folders');
        $this->invokeService->expects(self::once())->method('request')->with($host, 'GET', '/api/folders')->willReturn($response);

        $this->subscriber->onKernelRequest($event);
    }
}
