<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\RemoteHost\Authenticator;

use FD\LogViewer\Service\RemoteHost\Authenticator\AuthenticatorFactory;
use FD\LogViewer\Service\RemoteHost\Authenticator\BasicAuthAuthenticator;
use FD\LogViewer\Service\RemoteHost\Authenticator\BearerAuthenticator;
use FD\LogViewer\Service\RemoteHost\Authenticator\HeaderAuthenticator;
use FD\LogViewer\Tests\resources\Unit\MockAuthenticator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(AuthenticatorFactory::class)]
class AuthenticatorFactoryTest extends TestCase
{
    private AuthenticatorFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new AuthenticatorFactory();
    }

    public function testGetAuthenticator(): void
    {
        static::assertInstanceOf(BasicAuthAuthenticator::class, $this->factory->getAuthenticator('basic'));
        static::assertInstanceOf(BearerAuthenticator::class, $this->factory->getAuthenticator('bearer'));
        static::assertInstanceOf(HeaderAuthenticator::class, $this->factory->getAuthenticator('header'));
        static::assertInstanceOf(MockAuthenticator::class, $this->factory->getAuthenticator(MockAuthenticator::class));
    }

    public function testGetAuthenticatorInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid authenticator type: foobar.');
        $this->factory->getAuthenticator('foobar');
    }
}
