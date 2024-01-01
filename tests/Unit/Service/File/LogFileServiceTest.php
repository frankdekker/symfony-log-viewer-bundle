<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Service\File;

use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\LogFolder;
use FD\SymfonyLogViewerBundle\Entity\LogFolderCollection;
use FD\SymfonyLogViewerBundle\Service\File\LogFileService;
use FD\SymfonyLogViewerBundle\Service\FinderFactory;
use FD\SymfonyLogViewerBundle\Service\Folder\LogFolderFactory;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

#[CoversClass(LogFileService::class)]
class LogFileServiceTest extends TestCase
{
    private FinderFactory&MockObject $folderService;
    private LogFolderFactory&MockObject $logFolderFactory;
    private LogFileService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->folderService    = $this->createMock(FinderFactory::class);
        $this->logFolderFactory = $this->createMock(LogFolderFactory::class);
        $this->service          = new LogFileService($this->folderService, $this->logFolderFactory);
    }

    public function testGetFilesAndFolders(): void
    {
        $finder  = $this->createMock(Finder::class);
        $folders = new LogFolderCollection();

        $this->folderService->expects(self::once())->method('createForConfig')->willReturn($finder);
        $this->logFolderFactory->expects(self::once())->method('createFromFiles')->with($finder)->willReturn($folders);

        static::assertSame($folders, $this->service->getFilesAndFolders());
    }

    public function testFindFileByIdentifier(): void
    {
        $file   = new LogFile('identifier', 'path', 'relative', 11111, 22222, 33333);
        $folder = new LogFolder('identifier', 'path', 'relative', 11111, 22222);
        $folder->addFile($file);
        $folders = new LogFolderCollection();
        $folders->getOrAdd('folderA', static fn() => $folder);

        $finder = $this->createMock(Finder::class);

        $this->folderService->expects(self::once())->method('createForConfig')->willReturn($finder);
        $this->logFolderFactory->expects(self::once())->method('createFromFiles')->with($finder)->willReturn($folders);

        static::assertSame($file, $this->service->findFileByIdentifier('identifier'));
    }
}
