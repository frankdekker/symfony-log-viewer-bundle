<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\resources\Unit;

use FD\LogViewer\Service\RemoteHost\Authenticator\AuthenticatorInterface;
use LogicException;

class MockAuthenticator implements AuthenticatorInterface
{
    /**
     * @inheritDoc
     */
    public function authenticate(array $hostOptions, array $requestOptions): array
    {
        throw new LogicException('Not implemented');
    }
}
