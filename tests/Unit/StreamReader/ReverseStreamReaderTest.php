<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\StreamReader;

use FD\LogViewer\StreamReader\AbstractStreamReader;
use FD\LogViewer\StreamReader\ReverseStreamReader;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReverseStreamReader::class)]
#[CoversClass(AbstractStreamReader::class)]
class ReverseStreamReaderTest extends TestCase
{
    private string $path;

    protected function setUp(): void
    {
        parent::setUp();
        $this->path = vfsStream::setup('root', 777, ['test.log' => "line1\nline2\nline3\nline4\nline5\n"])->url();
    }

    public function testReaderIterator(): void
    {
        $reader = $this->createReader();

        $lines = iterator_to_array($reader);
        static::assertCount(5, $lines);
        static::assertSame("line5\n", $lines[0]);
        static::assertSame("line1\n", $lines[4]);
        static::assertTrue($reader->isEOF());
    }

    public function testReaderWithOffset(): void
    {
        $reader = $this->createReader();

        $lines = [];
        foreach ($reader as $line) {
            $lines[] = $line;
            if (count($lines) >= 2) {
                break;
            }
        }

        static::assertFalse($reader->isEOF());

        $reader = $this->createReader($reader->getPosition());
        foreach ($reader as $line) {
            $lines[] = $line;
        }

        static::assertCount(5, $lines);
        static::assertTrue($reader->isEOF());
    }

    public function testReaderWithBuffer(): void
    {
        $reader = $this->createReader(bufferSize: 13);

        $lines = iterator_to_array($reader);
        static::assertCount(5, $lines);
        static::assertSame("line5\n", $lines[0]);
        static::assertSame("line1\n", $lines[4]);
        static::assertTrue($reader->isEOF());
    }

    private function createReader(?int $offset = null, int $bufferSize = 50000): ReverseStreamReader
    {
        $handle = fopen($this->path . '/test.log', 'rb');
        static::assertNotFalse($handle);

        return new ReverseStreamReader($handle, $offset, $bufferSize);
    }
}
