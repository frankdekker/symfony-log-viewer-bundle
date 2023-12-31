<?php
declare(strict_types=1);

namespace Entity;

use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\LogFolder;
use FD\SymfonyLogViewerBundle\Entity\LogFolderCollection;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogFolderCollection::class)]
class LogFolderCollectionTest extends TestCase
{
    private LogFolderCollection $collection;

    protected function setUp(): void
    {
        $this->collection = new LogFolderCollection();
    }

    public function testFirst(): void
    {
        $folderA = new LogFolder('identifier', 'path', 'relative', 11111, 22222);
        $folderB = new LogFolder('identifier', 'path', 'relative', 11111, 22222);

        $this->collection->getOrAdd('folderA', static fn() => $folderA);
        $this->collection->getOrAdd('folderB', static fn() => $folderB);

        static::assertSame($folderA, $this->collection->first());
        static::assertSame($folderB, $this->collection->first(static fn($folder) => $folder === $folderB));
        static::assertNull($this->collection->first(static fn() => false));
    }

    public function testFirstFile(): void
    {
        $fileA   = new LogFile('identifier', 'path', 'relative', 11111, 22222, 33333);
        $folderA = new LogFolder('identifier', 'path', 'relative', 11111, 22222);
        $folderA->addFile($fileA);

        $fileB   = new LogFile('identifier', 'path', 'relative', 11111, 22222, 33333);
        $folderB = new LogFolder('identifier', 'path', 'relative', 11111, 22222);
        $folderB->addFile($fileB);

        $this->collection->getOrAdd('folderA', static fn() => $folderA);
        $this->collection->getOrAdd('folderB', static fn() => $folderB);

        static::assertSame($fileA, $this->collection->firstFile());
        static::assertSame($fileA, $this->collection->firstFile(static fn($file) => $file === $fileA));
        static::assertNull($this->collection->firstFile(static fn() => false));
    }

    public function testToArray(): void
    {
        $folderA = new LogFolder('identifier', 'path', 'relative', 11111, 22222);
        $folderB = new LogFolder('identifier', 'path', 'relative', 11111, 22222);

        $this->collection->getOrAdd('folderA', static fn() => $folderA);
        $this->collection->getOrAdd('folderB', static fn() => $folderB);

        static::assertSame([$folderA, $folderB], $this->collection->toArray());
    }
}
