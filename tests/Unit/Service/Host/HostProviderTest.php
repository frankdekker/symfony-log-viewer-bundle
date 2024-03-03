<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Host;

use ArrayIterator;
use FD\LogViewer\Entity\Config\HostConfig;
use FD\LogViewer\Service\Host\HostProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(HostProvider::class)]
class HostProviderTest extends TestCase
{
    private HostConfig $configA;
    private HostConfig $configB;
    private HostProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->configA  = new HostConfig('local', 'Local', null);
        $this->configB  = new HostConfig('remote', 'Remote', 'url');
        $this->provider = new HostProvider(new ArrayIterator([$this->configA, $this->configB]));
    }

    public function testGetHostByKey(): void
    {
        self::assertSame($this->configA, $this->provider->getHostByKey('local'));
        self::assertSame($this->configB, $this->provider->getHostByKey('remote'));
        self::assertNull($this->provider->getHostByKey('unknown'));
    }

    public function testGetHosts(): void
    {
        self::assertSame(['local' => 'Local', 'remote' => 'Remote'], $this->provider->getHosts());
    }
}
