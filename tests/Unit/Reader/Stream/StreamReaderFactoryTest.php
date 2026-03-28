<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Reader\Stream;

use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Reader\Stream\CompressedStreamReaderFactory;
use FD\LogViewer\Reader\Stream\ForwardStreamReader;
use FD\LogViewer\Reader\Stream\StreamReaderFactory;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SplFileInfo;

#[CoversClass(StreamReaderFactory::class)]
class StreamReaderFactoryTest extends TestCase
{
    private string $path;
    private StreamReaderFactory $streamReaderFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->path                = vfsStream::setup('root', 777, ['test.log' => "line1\nline2\n"])->url() . '/test.log';
        $this->streamReaderFactory = new StreamReaderFactory($this->createMock(CompressedStreamReaderFactory::class));
    }

    public function testCreateForFileForwardDirection(): void
    {
        $reader = $this->streamReaderFactory->createForFile(new SplFileInfo($this->path), DirectionEnum::Asc, null);
        static::assertSame(["line1\n", "line2\n"], iterator_to_array($reader));
    }

    public function testCreateForFileBackwardDirection(): void
    {
        $reader = $this->streamReaderFactory->createForFile(new SplFileInfo($this->path), DirectionEnum::Desc, null);
        static::assertSame(["line2\n", "line1\n"], iterator_to_array($reader));
    }

    public function testCreateForFileInvalidFile(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not open file "foobar"');
        $this->streamReaderFactory->createForFile(new SplFileInfo('foobar'), DirectionEnum::Asc, null);
    }

    public function testCreateForCompressedFileDelegatesToCompressedFactory(): void
    {
        $mockReader  = $this->createMock(ForwardStreamReader::class);
        $mockFactory = $this->createMock(CompressedStreamReaderFactory::class);
        $mockFactory->expects(static::once())->method('createForFile')->willReturn($mockReader);

        $factory = new StreamReaderFactory($mockFactory);
        $result  = $factory->createForFile(new SplFileInfo('fake.gz'), DirectionEnum::Asc, null);
        static::assertSame($mockReader, $result);
    }

    public function testCreateForCompressedFileWithDescDirectionThrows(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Reading compressed log files in descending order is not supported.');
        $this->streamReaderFactory->createForFile(new SplFileInfo('fake.gz'), DirectionEnum::Desc, null);
    }
}
