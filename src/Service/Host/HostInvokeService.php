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
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class HostInvokeService
{
    public function __construct(private readonly ?HttpClientInterface $httpClient = null)
    {
    }

    /**
     * @param array<key-of<HttpClientInterface::OPTIONS_DEFAULTS>, mixed> $options
     *
     * @throws Throwable
     */
    public function request(HostConfig $hostConfig, string $method, string $url, array $options): string
    {
        if ($hostConfig->authentication !== null) {
            $options = $this->getAuthenticator($hostConfig->authentication->type)->authenticate($hostConfig->authentication->options, $options);
        }

        if ($this->httpClient === null) {
            throw new LogicException('No HttpClientInterface registered. Try running "composer require symfony/http-client".');
        }

        return $this->httpClient->request($method, rtrim((string)$hostConfig->host, '/') . '/' . ltrim($url, '/'), $options)->getContent();
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
