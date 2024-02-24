<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\RemoteHost\Authenticator;

class BearerAuthenticator implements AuthenticatorInterface
{
    /**
     * @inheritDoc
     */
    public function authenticate(array $hostOptions, array $requestOptions): array
    {
        assert(array_key_exists('token', $hostOptions));
        assert(is_string($hostOptions['token']));

        $requestOptions['headers']['Authorization'] = 'Bearer ' . $hostOptions['token']; // @phpstan-ignore-line

        return $requestOptions;
    }
}
