<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Reader\Stream;

use FD\LogViewer\Reader\Stream\CompressedStreamReaderFactory;
use FD\LogViewer\Reader\Stream\ForwardStreamReader;
use FD\LogViewer\Tests\Utility\ExtensionMock;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use SplFileInfo;

#[CoversClass(CompressedStreamReaderFactory::class)]
class CompressedStreamReaderFactoryTest extends TestCase
{
    private string $gzPath;
    private CompressedStreamReaderFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->gzPath = tempnam(sys_get_temp_dir(), 'test') . '.gz';
        $handle       = gzopen($this->gzPath, 'wb');
        static::assertNotFalse($handle);

        gzwrite($handle, "line1\nline2\nline3\nline4\nline5\n");
        gzclose($handle);
        $this->factory = new CompressedStreamReaderFactory();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        unlink($this->gzPath);
        ExtensionMock::$zlibLoaded = true;
    }

    public function testCreateForGzFile(): void
    {
        $reader = $this->factory->createForFile(new SplFileInfo($this->gzPath), null);
        static::assertSame(["line1\n", "line2\n", "line3\n", "line4\n", "line5\n"], iterator_to_array($reader));
    }

    public function testCreateForGzFileInvalidFile(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Could not open file "foobar.gz"');
        $this->factory->createForFile(new SplFileInfo('foobar.gz'), null);
    }

    public function testCreateForGzFileWithoutZlibExtension(): void
    {
        ExtensionMock::$zlibLoaded = false;
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('The "zlib" PHP extension is required to read .gz compressed log files.');
        $this->factory->createForFile(new SplFileInfo($this->gzPath), null);
    }

    public function testCreateForUnsupportedExtension(): void
    {
        $path = tempnam(sys_get_temp_dir(), 'test') . '.bz2';
        touch($path);

        try {
            $this->expectException(RuntimeException::class);
            $this->expectExceptionMessage('Unsupported compressed file extension "bz2".');
            $this->factory->createForFile(new SplFileInfo($path), null);
        } finally {
            unlink($path);
        }
    }
}
