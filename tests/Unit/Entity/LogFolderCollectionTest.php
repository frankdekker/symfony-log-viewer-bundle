<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Entity;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\LogFolderCollection;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogFolderCollection::class)]
class LogFolderCollectionTest extends TestCase
{
    use TestEntityTrait;

    private LogFolderCollection $collection;

    protected function setUp(): void
    {
        $this->collection = new LogFolderCollection($this->createMock(LogFilesConfig::class));
    }

    public function testFirst(): void
    {
        $folderA = $this->createLogFolder(['collection' => $this->collection]);
        $folderB = $this->createLogFolder(['collection' => $this->collection]);

        $this->collection->getOrAdd('folderA', static fn() => $folderA);
        $this->collection->getOrAdd('folderB', static fn() => $folderB);

        static::assertSame($folderA, $this->collection->first());
        static::assertSame($folderB, $this->collection->first(static fn($folder) => $folder === $folderB));
        static::assertNull($this->collection->first(static fn() => false));
    }

    public function testFirstFile(): void
    {
        $folderA = $this->createLogFolder(['collection' => $this->collection]);
        $fileA   = $this->createLogFile(['folder' => $folderA]);
        $folderA->addFile($fileA);

        $folderB = $this->createLogFolder(['collection' => $this->collection]);
        $fileB   = $this->createLogFile(['folder' => $folderB]);
        $folderB->addFile($fileB);

        $this->collection->getOrAdd('folderA', static fn() => $folderA);
        $this->collection->getOrAdd('folderB', static fn() => $folderB);

        static::assertSame($fileA, $this->collection->firstFile());
        static::assertSame($fileA, $this->collection->firstFile(static fn($file) => $file === $fileA));
        static::assertNull($this->collection->firstFile(static fn() => false));
    }

    public function testToArray(): void
    {
        $folderA = $this->createLogFolder(['collection' => $this->collection]);
        $folderB = $this->createLogFolder(['collection' => $this->collection]);

        $this->collection->getOrAdd('folderA', static fn() => $folderA);
        $this->collection->getOrAdd('folderB', static fn() => $folderB);

        static::assertSame([$folderA, $folderB], $this->collection->toArray());
    }
}
