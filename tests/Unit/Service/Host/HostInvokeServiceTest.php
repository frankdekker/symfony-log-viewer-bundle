<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Host;

use FD\LogViewer\Entity\Config\HostAuthenticationConfig;
use FD\LogViewer\Entity\Config\HostConfig;
use FD\LogViewer\Service\Host\HostInvokeService;
use FD\LogViewer\Service\Host\ResponseFactory;
use FD\LogViewer\Service\RemoteHost\Authenticator\AuthenticatorFactory;
use FD\LogViewer\Service\RemoteHost\Authenticator\AuthenticatorInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\Exception\LogicException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

#[CoversClass(HostInvokeService::class)]
class HostInvokeServiceTest extends TestCase
{
    private AuthenticatorFactory&MockObject $authenticatorFactory;
    private AuthenticatorInterface&MockObject $authenticator;
    private ResponseFactory&MockObject $responseFactory;
    private HttpClientInterface&MockObject $httpClient;
    private HostInvokeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authenticatorFactory = $this->createMock(AuthenticatorFactory::class);
        $this->authenticator        = $this->createMock(AuthenticatorInterface::class);
        $this->responseFactory      = $this->createMock(ResponseFactory::class);
        $this->httpClient           = $this->createMock(HttpClientInterface::class);
        $this->service              = new HostInvokeService($this->authenticatorFactory, $this->responseFactory, $this->httpClient);
    }

    /**
     * @throws Throwable
     */
    public function testRequestExceptionOnAbsentHttpClient(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('No HttpClientInterface registered.');

        $service = new HostInvokeService($this->authenticatorFactory, $this->responseFactory);
        $service->request($this->createMock(HostConfig::class), 'GET', '/', []);
    }

    /**
     * @throws Throwable
     */
    public function testRequestWithAuthentication(): void
    {
        $host         = new HostConfig('key', 'name', 'host', new HostAuthenticationConfig('basic'));
        $httpResponse = $this->createMock(ResponseInterface::class);
        $response     = $this->createMock(StreamedResponse::class);

        $this->authenticatorFactory->expects(self::once())->method('getAuthenticator')->with('basic')->willReturn($this->authenticator);
        $this->authenticator->expects(self::once())->method('authenticate')->with($host->authentication?->options, [])->willReturn([]);
        $this->httpClient->expects(self::once())->method('request')->with('GET', 'host/api/folder', [])->willReturn($httpResponse);
        $this->responseFactory->expects(self::once())->method('toStreamedResponse')->with($this->httpClient, $httpResponse)->willReturn($response);

        $this->service->request($host, 'GET', '/api/folder', []);
    }
}
