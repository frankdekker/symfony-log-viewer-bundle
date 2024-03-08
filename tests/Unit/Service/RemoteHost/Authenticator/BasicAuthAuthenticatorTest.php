<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\RemoteHost\Authenticator;

use FD\LogViewer\Service\RemoteHost\Authenticator\BasicAuthAuthenticator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(BasicAuthAuthenticator::class)]
class BasicAuthAuthenticatorTest extends TestCase
{
    private BasicAuthAuthenticator $authenticator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticator = new BasicAuthAuthenticator();
    }

    public function testAuthenticate(): void
    {
        $hostOptions = ['username' => 'foo', 'password' => 'bar'];
        $expected    = ['headers' => ['Authorization' => 'Basic Zm9vOmJhcg==']];

        static::assertSame($expected, $this->authenticator->authenticate($hostOptions, []));
    }
}
