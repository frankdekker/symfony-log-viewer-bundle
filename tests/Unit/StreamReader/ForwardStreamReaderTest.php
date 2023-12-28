<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\StreamReader;

use FD\SymfonyLogViewerBundle\StreamReader\AbstractStreamReader;
use FD\SymfonyLogViewerBundle\StreamReader\ForwardStreamReader;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ForwardStreamReader::class)]
#[CoversClass(AbstractStreamReader::class)]
class ForwardStreamReaderTest extends TestCase
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
        static::assertSame("line1\n", $lines[0]);
        static::assertSame("line5\n", $lines[4]);
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

    private function createReader(int $offset = 0): ForwardStreamReader
    {
        $handle = fopen($this->path . '/test.log', 'rb');
        static::assertNotFalse($handle);

        return new ForwardStreamReader($handle, $offset);
    }
}
