<?php

declare(strict_types=1);

namespace FD\LogViewer\EventSubscriber;

use FD\LogViewer\Controller\RemoteHostProxyInterface;
use FD\LogViewer\Entity\Config\HostConfig;
use FD\LogViewer\Routing\RouteService;
use FD\LogViewer\Service\Host\HostInvokeService;
use FD\LogViewer\Service\Host\HostProvider;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

/**
 * Listen for requests with 'host' query, and forward request if the host is a remote host.
 */
#[AsEventListener(KernelEvents::REQUEST, 'onKernelRequest')]
class RemoteHostProxySubscriber
{
    public function __construct(
        private readonly ControllerResolverInterface $controllerResolver,
        private readonly RouteService $routeService,
        private readonly HostProvider $hostProvider,
        private readonly HostInvokeService $invokeService,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        // must be log-viewer controller
        if ($request->query->has('host') === false || $this->isControllerSupported($request) === false) {
            return;
        }

        $host = $this->hostProvider->getHostByKey($request->query->get('host', 'localhost'));
        if ($host === null || $host->isLocal()) {
            return;
        }

        // must have a route
        $uri = $this->routeService->getRelativeUriFor($request);
        if ($uri === null) {
            return;
        }

        // invoke the request
        $event->setResponse($this->invoke($host, $uri, $request));
    }

    private function isControllerSupported(Request $request): bool
    {
        $callable = $this->controllerResolver->getController($request);

        return is_array($callable) && isset($callable[0]) && $callable[0] instanceof RemoteHostProxyInterface;
    }

    /**
     * @throws Throwable
     */
    private function invoke(HostConfig $host, string $uri, Request $request): Response
    {
        $query = $request->query->all();
        unset($query['host']);

        $headers = [
            'Accept'            => $request->headers->get('Accept', 'application/json'),
            'X-Forwarded-Host'  => $request->getHost(),
            'X-Forwarded-Port'  => $request->getPort(),
            'X-Forwarded-Proto' => $request->getScheme()
        ];

        return $this->invokeService->request($host, $request->getMethod(), $uri, ['query' => $query, 'headers' => $headers]);
    }
}
