<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\StreamReader;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\StreamReader\StreamReaderFactory;
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
        $this->streamReaderFactory = new StreamReaderFactory();
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
}
