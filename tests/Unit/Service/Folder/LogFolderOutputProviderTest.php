<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Service\Folder;

use FD\SymfonyLogViewerBundle\Entity\LogFolderCollection;
use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Entity\Output\LogFolderOutput;
use FD\SymfonyLogViewerBundle\Service\File\LogFileService;
use FD\SymfonyLogViewerBundle\Service\Folder\LogFolderOutputFactory;
use FD\SymfonyLogViewerBundle\Service\Folder\LogFolderOutputProvider;
use FD\SymfonyLogViewerBundle\Service\Folder\LogFolderOutputSorter;
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
        $folders      = new LogFolderCollection();
        $folderOutput = new LogFolderOutput('identifier', 'path', 'url', true, 123456, []);

        $this->folderService->expects(self::once())->method('getFilesAndFolders')->willReturn($folders);
        $this->folderOutputFactory->expects(self::once())->method('createFromFolders')->with($folders)->willReturn([$folderOutput]);
        $this->sorter->expects(self::once())->method('sort')->with([$folderOutput], DirectionEnum::Desc)->willReturn([$folderOutput]);

        static::assertSame([$folderOutput], $this->provider->provide(DirectionEnum::Desc));
    }
}
