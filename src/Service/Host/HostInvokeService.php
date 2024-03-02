<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\Host;

use FD\LogViewer\Entity\Config\HostConfig;
use FD\LogViewer\Service\RemoteHost\Authenticator\AuthenticatorInterface;
use FD\LogViewer\Service\RemoteHost\Authenticator\BasicAuthAuthenticator;
use FD\LogViewer\Service\RemoteHost\Authenticator\BearerAuthenticator;
use FD\LogViewer\Service\RemoteHost\Authenticator\HeaderAuthenticator;
use InvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class HostInvokeService
{
    public function __construct(
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
            $options = $this->getAuthenticator($hostConfig->authentication->type)->authenticate($hostConfig->authentication->options, $options);
        }

        if ($this->httpClient === null) {
            throw new LogicException(
                'No HttpClientInterface registered. Try running "composer require symfony/http-client" and ensure to enable ' .
                'it via framework.http_client.enabled=true in /config/packages/framework.yaml.'
            );
        }

        $httpResponse = $this->httpClient->request($method, rtrim((string)$hostConfig->host, '/') . '/' . ltrim($url, '/'), $options);

        // transform to symfony response
        return $this->responseFactory->toStreamedResponse($this->httpClient, $httpResponse);
    }

    private function getAuthenticator(string $type): AuthenticatorInterface
    {
        if (is_a($type, AuthenticatorInterface::class, true)) {
            return new $type();
        }

        return match ($type) {
            'basic'             => new BasicAuthAuthenticator(),
            'bearer'            => new BearerAuthenticator(),
            'header', 'headers' => new HeaderAuthenticator(),
            default             => throw new InvalidArgumentException(
                'Invalid authenticator type: ' . $type .
                '. Expecting, `basic`, `bearer`, `header` or `headers` or implementation of AuthenticatorInterface'
            )
        };
    }
}
