<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Service\Folder;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Entity\Output\LogFileOutput;
use FD\SymfonyLogViewerBundle\Entity\Output\LogFolderOutput;
use FD\SymfonyLogViewerBundle\Service\Folder\LogFolderOutputSorter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogFolderOutputSorter::class)]
class LogFolderOutputSorterTest extends TestCase
{
    private LogFolderOutputSorter $sorter;
    private LogFileOutput $fileA;
    private LogFileOutput $fileB;
    private LogFileOutput $fileC;
    private LogFileOutput $fileD;
    private LogFolderOutput $folderA;
    private LogFolderOutput $folderB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileA   = new LogFileOutput('identifier', 'name', 'sizeFormatted', 'downloadUrl', 0, 333333, true);
        $this->fileB   = new LogFileOutput('identifier', 'name', 'sizeFormatted', 'downloadUrl', 0, 444444, true);
        $this->fileC   = new LogFileOutput('identifier', 'name', 'sizeFormatted', 'downloadUrl', 0, 666666, true);
        $this->fileD   = new LogFileOutput('identifier', 'name', 'sizeFormatted', 'downloadUrl', 0, 555555, true);
        $this->folderA = new LogFolderOutput('identifier', 'path', 'url', true, 222222, [$this->fileA, $this->fileB]);
        $this->folderB = new LogFolderOutput('identifier', 'path', 'url', true, 111111, [$this->fileC, $this->fileD]);

        $this->sorter = new LogFolderOutputSorter();
    }

    public function testSortAscending(): void
    {
        $result = $this->sorter->sort([$this->folderA, $this->folderB], DirectionEnum::Asc);
        static::assertSame([$this->folderB, $this->folderA], $result);
        static::assertSame([$this->fileD, $this->fileC], $result[0]->files);
        static::assertSame([$this->fileA, $this->fileB], $result[1]->files);
    }

    public function testSortDescending(): void
    {
        $result = $this->sorter->sort([$this->folderA, $this->folderB], DirectionEnum::Desc);
        static::assertSame([$this->folderA, $this->folderB], $result);
        static::assertSame([$this->fileC, $this->fileD], $result[1]->files);
        static::assertSame([$this->fileB, $this->fileA], $result[0]->files);
    }
}
