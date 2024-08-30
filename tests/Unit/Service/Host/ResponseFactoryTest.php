<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Host;

use FD\LogViewer\Service\Host\ResponseFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\Response\ResponseStream;
use Symfony\Contracts\HttpClient\ChunkInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use Throwable;

#[CoversClass(ResponseFactory::class)]
class ResponseFactoryTest extends TestCase
{
    private ResponseInterface&MockObject $httpResponse;
    private HttpClientInterface&MockObject $httpClient;
    private ResponseFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->httpClient   = $this->createMock(HttpClientInterface::class);
        $this->httpResponse = $this->createMock(ResponseInterface::class);
        $this->factory      = new ResponseFactory();
    }

    /**
     * @throws Throwable
     */
    public function testToStreamedResponse(): void
    {
        $chunk = $this->createMock(ChunkInterface::class);
        $chunk->method('getContent')->willReturn('chunk');
        $stream = new ResponseStream((static function () use ($chunk) {
            yield $chunk;
        })());

        $this->httpResponse->expects(self::once())->method('getStatusCode')->willReturn(200);
        $this->httpResponse->expects(self::once())
            ->method('getHeaders')
            ->willReturn(['content-type' => 'application/json', 'set-cookie' => 'cookie']);
        $this->httpClient->expects(self::once())->method('stream')->with($this->httpResponse)->willReturn($stream);

        $response = $this->factory->toStreamedResponse($this->httpClient, $this->httpResponse);

        static::assertArrayHasKey('content-type', $response->headers->all());
        static::assertArrayNotHasKey('set-cookie', $response->headers->all());

        ob_start();
        $response->sendContent();
        static::assertSame('chunk', ob_get_clean());
    }
}
