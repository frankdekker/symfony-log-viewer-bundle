<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File;

use ArrayIterator;
use FD\LogViewer\Service\File\LogFileParserInterface;
use FD\LogViewer\Service\File\LogFileParserProvider;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogFileParserProvider::class)]
class LogFileParserProviderTest extends TestCase
{
    private LogFileParserInterface&MockObject $logFileParser;
    private LogFileParserProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logFileParser = $this->createMock(LogFileParserInterface::class);
        $this->provider      = new LogFileParserProvider(new ArrayIterator(['identifier' => $this->logFileParser]));
    }

    public function testGet(): void
    {
        static::assertSame($this->logFileParser, $this->provider->get('identifier'));
    }

    public function testGetUnknown(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Log parser "unknown" not found.');
        $this->provider->get('unknown');
    }
}
