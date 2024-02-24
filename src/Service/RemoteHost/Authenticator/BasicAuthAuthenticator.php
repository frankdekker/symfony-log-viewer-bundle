<?php

declare(strict_types=1);

namespace FD\LogViewer\Service\RemoteHost\Authenticator;

class BasicAuthAuthenticator implements AuthenticatorInterface
{
    /**
     * @inheritDoc
     */
    public function authenticate(array $hostOptions, array $requestOptions): array
    {
        assert(array_key_exists('username', $hostOptions));
        assert(array_key_exists('password', $hostOptions));
        assert(is_string($hostOptions['username']));
        assert(is_string($hostOptions['password']));

        $authorization = 'Basic ' . base64_encode(sprintf('%s:%s', $hostOptions['username'], $hostOptions['password']));

        $requestOptions['headers']['Authorization'] = $authorization;

        return $requestOptions;
    }
}
