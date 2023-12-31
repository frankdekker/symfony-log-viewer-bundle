<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Service\Folder;

use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\LogFolder;
use FD\SymfonyLogViewerBundle\Service\Folder\LogFolderFactory;
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

        $collection = $this->factory->createFromFiles(['fileA' => $fileA, 'fileB' => $fileB]);

        $expectedFolder = new LogFolder('29c04485', 'path', 'relative-path', 222222, 444444);
        $expectedFolder->addFile(new LogFile('f218b241', 'pathname A', 'relative-path', 111111, 222222, 333333));
        $expectedFolder->addFile(new LogFile('9d0ad7b0', 'pathname B', 'relative-path', 111111, 333333, 444444));

        $folders = $collection->toArray();
        static::assertEquals([$expectedFolder], $folders);
    }
}
