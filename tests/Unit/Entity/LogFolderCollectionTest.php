<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\LogFolder;
use FD\LogViewer\Entity\LogFolderCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogFolderCollection::class)]
class LogFolderCollectionTest extends TestCase
{
    private LogFolderCollection $collection;

    protected function setUp(): void
    {
        $this->collection = new LogFolderCollection($this->createMock(LogFilesConfig::class));
    }

    public function testFirst(): void
    {
        $folderA = new LogFolder('identifier', 'path', 'relative', 11111, 22222, $this->collection);
        $folderB = new LogFolder('identifier', 'path', 'relative', 11111, 22222, $this->collection);

        $this->collection->getOrAdd('folderA', static fn() => $folderA);
        $this->collection->getOrAdd('folderB', static fn() => $folderB);

        static::assertSame($folderA, $this->collection->first());
        static::assertSame($folderB, $this->collection->first(static fn($folder) => $folder === $folderB));
        static::assertNull($this->collection->first(static fn() => false));
    }

    public function testFirstFile(): void
    {
        $folderA = new LogFolder('identifier', 'path', 'relative', 11111, 22222, $this->collection);
        $fileA   = new LogFile('identifier', 'path', 'relative', 11111, 22222, 33333, $folderA);
        $folderA->addFile($fileA);

        $folderB = new LogFolder('identifier', 'path', 'relative', 11111, 22222, $this->collection);
        $fileB   = new LogFile('identifier', 'path', 'relative', 11111, 22222, 33333, $folderB);
        $folderB->addFile($fileB);

        $this->collection->getOrAdd('folderA', static fn() => $folderA);
        $this->collection->getOrAdd('folderB', static fn() => $folderB);

        static::assertSame($fileA, $this->collection->firstFile());
        static::assertSame($fileA, $this->collection->firstFile(static fn($file) => $file === $fileA));
        static::assertNull($this->collection->firstFile(static fn() => false));
    }

    public function testToArray(): void
    {
        $folderA = new LogFolder('identifier', 'path', 'relative', 11111, 22222, $this->collection);
        $folderB = new LogFolder('identifier', 'path', 'relative', 11111, 22222, $this->collection);

        $this->collection->getOrAdd('folderA', static fn() => $folderA);
        $this->collection->getOrAdd('folderB', static fn() => $folderB);

        static::assertSame([$folderA, $folderB], $this->collection->toArray());
    }
}
