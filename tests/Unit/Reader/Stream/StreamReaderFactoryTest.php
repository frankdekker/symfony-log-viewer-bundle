<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Reader\Stream;

use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Reader\Stream\ForwardStreamReader;
use FD\LogViewer\Reader\Stream\StreamReaderFactory;
use FD\LogViewer\Tests\Utility\ExtensionMock;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SplFileInfo;

#[CoversClass(StreamReaderFactory::class)]
class StreamReaderFactoryTest extends TestCase
{
    private string $path;
    private string $gzPath;
    private StreamReaderFactory $streamReaderFactory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->path   = vfsStream::setup('root', 777, ['test.log' => "line1\nline2\n"])->url() . '/test.log';
        $this->gzPath = tempnam(sys_get_temp_dir(), 'test') . '.gz';
        $handle       = gzopen($this->gzPath, 'wb');
        static::assertNotFalse($handle);

        gzwrite($handle, "line1\nline2\nline3\nline4\nline5\n");
        gzclose($handle);
        $this->streamReaderFactory = new StreamReaderFactory();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unlink($this->gzPath);
        ExtensionMock::$zlibLoaded = true;
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

    public function testCreateForGzFileWithAscDirection(): void
    {
        $reader = $this->streamReaderFactory->createForFile(new SplFileInfo($this->gzPath), DirectionEnum::Asc, null);
        static::assertInstanceOf(ForwardStreamReader::class, $reader);
        static::assertSame(["line1\n", "line2\n", "line3\n", "line4\n", "line5\n"], iterator_to_array($reader));
    }

    public function testCreateForGzFileWithDescDirection(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Reading .gz compressed log files in descending order is not supported.');
        $this->streamReaderFactory->createForFile(new SplFileInfo($this->gzPath), DirectionEnum::Desc, null);
    }

    public function testCreateForGzFileInvalidFile(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not open file "foobar.gz"');
        $this->streamReaderFactory->createForFile(new SplFileInfo('foobar.gz'), DirectionEnum::Asc, null);
    }

    public function testCreateForGzFileWithoutZlibExtension(): void
    {
        ExtensionMock::$zlibLoaded = false;
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The "zlib" PHP extension is required to read .gz compressed log files.');
        $this->streamReaderFactory->createForFile(new SplFileInfo($this->gzPath), DirectionEnum::Asc, null);
    }
}
