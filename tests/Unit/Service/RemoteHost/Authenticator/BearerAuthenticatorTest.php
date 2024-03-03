<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\RemoteHost\Authenticator;

use FD\LogViewer\Service\RemoteHost\Authenticator\BearerAuthenticator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BearerAuthenticator::class)]
class BearerAuthenticatorTest extends TestCase
{
    private BearerAuthenticator $authenticator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticator = new BearerAuthenticator();
    }

    public function testAuthenticate(): void
    {
        $hostOptions = ['token' => 'foo'];
        $expected    = ['headers' => ['Authorization' => 'Bearer foo']];

        static::assertSame($expected, $this->authenticator->authenticate($hostOptions, []));
    }
}
