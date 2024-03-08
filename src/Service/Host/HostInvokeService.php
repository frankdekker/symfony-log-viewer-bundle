<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Host;

use FD\LogViewer\Entity\Config\HostConfig;
use FD\LogViewer\Service\RemoteHost\Authenticator\AuthenticatorFactory;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class HostInvokeService
{
    public function __construct(
        private readonly AuthenticatorFactory $authenticatorFactory,
        private readonly ResponseFactory $responseFactory,
        private readonly ?HttpClientInterface $httpClient = null
    ) {
    }

    /**
     * @param array<key-of<HttpClientInterface::OPTIONS_DEFAULTS>, mixed> $options
     *
     * @throws Throwable
     */
    public function request(HostConfig $hostConfig, string $method, string $url, array $options): Response
    {
        if ($this->httpClient === null) {
            throw new LogicException(
                'No HttpClientInterface registered. Try running "composer require symfony/http-client" and ensure to enable ' .
                'it via framework.http_client.enabled=true in /config/packages/framework.yaml.'
            );
        }

        if ($hostConfig->authentication !== null) {
            $options = $this->authenticatorFactory
                ->getAuthenticator($hostConfig->authentication->type)
                ->authenticate($hostConfig->authentication->options, $options);
        }

        $httpResponse = $this->httpClient->request($method, rtrim((string)$hostConfig->host, '/') . '/' . ltrim($url, '/'), $options);

        // transform to symfony response
        return $this->responseFactory->toStreamedResponse($this->httpClient, $httpResponse);
    }
}
