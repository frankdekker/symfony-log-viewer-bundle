<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File;

use ArrayIterator;
use FD\LogViewer\Entity\Config\FinderConfig;
use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\LogFolderCollection;
use FD\LogViewer\Service\File\LogFileService;
use FD\LogViewer\Service\FinderFactory;
use FD\LogViewer\Service\Folder\LogFolderFactory;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Finder\Finder;

#[CoversClass(LogFileService::class)]
class LogFileServiceTest extends TestCase
{
    use TestEntityTrait;

    private FinderFactory&MockObject $folderService;
    private LogFolderFactory&MockObject $logFolderFactory;
    private LogFileService $service;
    private FinderConfig $finderConfig;
    private LogFilesConfig $config;

    protected function setUp(): void
    {
        parent::setUp();
        $this->finderConfig = new FinderConfig(__DIR__, null, null, false, false);
        $this->config       = $this->createLogFileConfig(['finderConfig' => $this->finderConfig]);

        $this->folderService    = $this->createMock(FinderFactory::class);
        $this->logFolderFactory = $this->createMock(LogFolderFactory::class);
        $this->service          = new LogFileService(new ArrayIterator([$this->config]), $this->folderService, $this->logFolderFactory);
    }

    public function testGetFilesAndFolders(): void
    {
        $finder  = $this->createMock(Finder::class);
        $folders = new LogFolderCollection($this->config);

        $this->folderService->expects(self::once())->method('createForConfig')->with($this->finderConfig)->willReturn($finder);
        $this->logFolderFactory->expects(self::once())->method('createFromFiles')->with($this->config, $finder)->willReturn($folders);

        static::assertSame([$folders], $this->service->getFilesAndFolders());
    }

    public function testFindFileByIdentifier(): void
    {
        $folder = $this->createLogFolder();
        $file   = $this->createLogFile(['folder' => $folder]);
        $folder->addFile($file);
        $folders = new LogFolderCollection($this->config);
        $folders->getOrAdd('folderA', static fn() => $folder);

        $finder = $this->createMock(Finder::class);

        $this->folderService->expects(self::once())->method('createForConfig')->with($this->finderConfig)->willReturn($finder);
        $this->logFolderFactory->expects(self::once())->method('createFromFiles')->with($this->config, $finder)->willReturn($folders);

        static::assertSame($file, $this->service->findFileByIdentifier('identifier'));
    }

    public function testFindFileByIdentifierUnknownFile(): void
    {
        $folders = new LogFolderCollection($this->config);
        $finder  = $this->createMock(Finder::class);

        $this->folderService->expects(self::once())->method('createForConfig')->with($this->finderConfig)->willReturn($finder);
        $this->logFolderFactory->expects(self::once())->method('createFromFiles')->with($this->config, $finder)->willReturn($folders);

        static::assertNull($this->service->findFileByIdentifier('identifier'));
    }

    public function testFindFolderByIdentifier(): void
    {
        $folder  = $this->createLogFolder();
        $folders = new LogFolderCollection($this->config);
        $folders->getOrAdd('folderA', static fn() => $folder);

        $finder = $this->createMock(Finder::class);

        $this->folderService->expects(self::once())->method('createForConfig')->with($this->finderConfig)->willReturn($finder);
        $this->logFolderFactory->expects(self::once())->method('createFromFiles')->with($this->config, $finder)->willReturn($folders);

        static::assertSame($folder, $this->service->findFolderByIdentifier('identifier'));
    }

    public function testFindFolderByIdentifierUnknownFolder(): void
    {
        $folders = new LogFolderCollection($this->config);
        $finder  = $this->createMock(Finder::class);

        $this->folderService->expects(self::once())->method('createForConfig')->with($this->finderConfig)->willReturn($finder);
        $this->logFolderFactory->expects(self::once())->method('createFromFiles')->with($this->config, $finder)->willReturn($folders);

        static::assertNull($this->service->findFolderByIdentifier('identifier'));
    }
}
