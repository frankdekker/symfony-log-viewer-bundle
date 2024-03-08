<?php
declare(strict_types=1);

namespace FD\LogViewer\Service\RemoteHost\Authenticator;

class HeaderAuthenticator implements AuthenticatorInterface
{
    /**
     * @inheritDoc
     */
    public function authenticate(array $hostOptions, array $requestOptions): array
    {
        foreach ($hostOptions as $key => $value) {
            $requestOptions['headers'][$key] = $value; // @phpstan-ignore-line
        }

        return $requestOptions;
    }
}
