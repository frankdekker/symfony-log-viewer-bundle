<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Host;

use FD\LogViewer\Entity\Config\HostConfig;
use FD\LogViewer\Service\Host\HostInvokeService;
use FD\LogViewer\Service\Host\ResponseFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

#[CoversClass(HostInvokeService::class)]
class HostInvokeServiceTest extends TestCase
{
    private ResponseFactory&MockObject $responseFactory;
    private HttpClientInterface&MockObject $httpClient;
    private HostInvokeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->responseFactory = $this->createMock(ResponseFactory::class);
        $this->httpClient      = $this->createMock(HttpClientInterface::class);
        $this->service         = new HostInvokeService($this->responseFactory, $this->httpClient);
    }

    /**
     * @throws Throwable
     */
    public function testRequestExceptionOnAbsentHttpClient(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('No HttpClientInterface registered.');

        $service = new HostInvokeService($this->responseFactory);
        $service->request($this->createStub(HostConfig::class), 'GET', '/', []);
    }
}
