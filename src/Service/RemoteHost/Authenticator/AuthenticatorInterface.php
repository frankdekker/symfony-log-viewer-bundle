<?php

declare(strict_types=1);

namespace FD\LogViewer\Service\RemoteHost\Authenticator;

use Symfony\Contracts\HttpClient\HttpClientInterface;

interface AuthenticatorInterface
{
    /**
     * @template T of array<key-of<HttpClientInterface::OPTIONS_DEFAULTS>, mixed>
     * @param array<string, string>|array{username: string, password: string}|array{token: string} $hostOptions
     * @param T                                                                                   $requestOptions
     *
     * @return T
     */
    public function authenticate(array $hostOptions, array $requestOptions): array;
}
