<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\Folder;

use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\LogFolderCollection;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Output\LogFolderOutput;
use FD\LogViewer\Service\File\LogFileService;
use FD\LogViewer\Service\Folder\LogFolderOutputFactory;
use FD\LogViewer\Service\Folder\LogFolderOutputProvider;
use FD\LogViewer\Service\Folder\LogFolderOutputSorter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogFolderOutputProvider::class)]
class LogFolderOutputProviderTest extends TestCase
{
    private LogFileService&MockObject $folderService;
    private LogFolderOutputFactory&MockObject $folderOutputFactory;
    private LogFolderOutputSorter&MockObject $sorter;
    private LogFolderOutputProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->folderService       = $this->createMock(LogFileService::class);
        $this->folderOutputFactory = $this->createMock(LogFolderOutputFactory::class);
        $this->sorter              = $this->createMock(LogFolderOutputSorter::class);
        $this->provider            = new LogFolderOutputProvider($this->folderService, $this->folderOutputFactory, $this->sorter);
    }

    public function testProvide(): void
    {
        $folders      = new LogFolderCollection($this->createMock(LogFilesConfig::class));
        $folderOutput = new LogFolderOutput('identifier', 'path', true, true, 123456, []);

        $this->folderService->expects(self::once())->method('getFilesAndFolders')->willReturn([$folders]);
        $this->folderOutputFactory->expects(self::once())->method('createFromFolders')->with($folders)->willReturn([$folderOutput]);
        $this->sorter->expects(self::once())->method('sort')->with([$folderOutput], DirectionEnum::Desc)->willReturn([$folderOutput]);

        static::assertSame([$folderOutput], $this->provider->provide(DirectionEnum::Desc));
    }
}
