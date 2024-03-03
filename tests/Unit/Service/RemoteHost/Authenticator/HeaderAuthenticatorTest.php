<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\RemoteHost\Authenticator;

use FD\LogViewer\Service\RemoteHost\Authenticator\HeaderAuthenticator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HeaderAuthenticator::class)]
class HeaderAuthenticatorTest extends TestCase
{
    private HeaderAuthenticator $authenticator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticator = new HeaderAuthenticator();
    }

    public function testAuthenticate(): void
    {
        static::assertSame(['headers' => ['foo' => 'bar']], $this->authenticator->authenticate(['foo' => 'bar'], []));
    }
}
