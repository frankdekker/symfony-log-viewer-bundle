<?php

declare(strict_types=1);

namespace FD\LogViewer\EventSubscriber;

use FD\LogViewer\Controller\ProxyControllerInterface;
use FD\LogViewer\Routing\RouteService;
use FD\LogViewer\Service\Host\HostInvokeService;
use FD\LogViewer\Service\Host\HostProvider;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

/**
 * Listen for requests with 'host' query, and forward request if the host is a remote host.
 */
#[AsEventListener(KernelEvents::REQUEST, 'onKernelRequest')]
class RemoteRequestProxySubscriber
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
        $host    = $this->hostProvider->getHostByKey($request->query->get('host', 'localhost'));
        if ($host === null || $host->isLocal()) {
            return;
        }

        // must be log-viewer controller
        if ($this->isControllerSupported($request) === false) {
            return;
        }

        // must have a route
        $uri = $this->getRelativeUri($request);
        if ($uri === null) {
            return;
        }

        $query = $request->query->all();
        unset($query['host']);
        $headers = [
            'Accept'            => $request->headers->get('Accept', 'application/json'),
            'X-Forwarded-Host'  => $request->getHost(),
            'X-Forwarded-Port'  => $request->getPort(),
            'X-Forwarded-Proto' => $request->getScheme()
        ];

        $response = $this->invokeService->request($host, $request->getMethod(), $uri, ['query' => $query, 'headers' => $headers]);

        $event->setResponse($response);
    }

    private function isControllerSupported(Request $request): bool
    {
        $callable = $this->controllerResolver->getController($request);

        return is_array($callable) && isset($callable[0]) && $callable[0] instanceof ProxyControllerInterface;
    }

    private function getRelativeUri(Request $request): ?string
    {
        $route = $request->attributes->get('_route');
        if (is_string($route) === false) {
            return null;
        }

        return $this->routeService->getRelativeUriFor($route);
    }
}
