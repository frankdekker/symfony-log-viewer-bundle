<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\RemoteHost\Authenticator;

use InvalidArgumentException;

class AuthenticatorFactory
{
    public function getAuthenticator(string $type): AuthenticatorInterface
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
