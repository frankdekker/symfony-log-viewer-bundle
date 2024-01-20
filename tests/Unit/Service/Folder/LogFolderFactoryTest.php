<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Folder;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\LogFolder;
use FD\LogViewer\Entity\LogFolderCollection;
use FD\LogViewer\Service\Folder\LogFolderFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\SplFileInfo;

#[CoversClass(LogFolderFactory::class)]
class LogFolderFactoryTest extends TestCase
{
    private LogFolderFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();
        $this->factory = new LogFolderFactory();
    }

    public function testCreateFromFiles(): void
    {
        $config = $this->createMock(LogFilesConfig::class);

        $fileA = $this->createMock(SplFileInfo::class);
        $fileA->method('getPathname')->willReturn('pathname A');
        $fileA->method('getPath')->willReturn('path');
        $fileA->method('getRelativePath')->willReturn('relative-path');
        $fileA->method('getSize')->willReturn(111111);
        $fileA->method('getCTime')->willReturn(222222);
        $fileA->method('getMTime')->willReturn(333333);

        $fileB = $this->createMock(SplFileInfo::class);
        $fileB->method('getPathname')->willReturn('pathname B');
        $fileB->method('getPath')->willReturn('path');
        $fileB->method('getRelativePath')->willReturn('relative-path');
        $fileB->method('getSize')->willReturn(111111);
        $fileB->method('getCTime')->willReturn(333333);
        $fileB->method('getMTime')->willReturn(444444);

        $collection = $this->factory->createFromFiles($config, ['fileA' => $fileA, 'fileB' => $fileB]);

        $expectedCollection = new LogFolderCollection($config);
        $expectedFolder = new LogFolder('29c04485', 'path', 'relative-path', 222222, 444444, $expectedCollection);
        $expectedFolder->addFile(new LogFile('f218b241', 'pathname A', 'relative-path', 111111, 222222, 333333, $expectedFolder));
        $expectedFolder->addFile(new LogFile('9d0ad7b0', 'pathname B', 'relative-path', 111111, 333333, 444444, $expectedFolder));
        $expectedCollection->getOrAdd('path', static fn() => $expectedFolder);

        static::assertEquals([$expectedFolder], $collection->toArray());
    }
}
