<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Service\File;

use FD\SymfonyLogViewerBundle\Entity\Config\FinderConfig;
use FD\SymfonyLogViewerBundle\Entity\Config\LogFilesConfig;
use FD\SymfonyLogViewerBundle\Entity\Index\LogIndex;
use FD\SymfonyLogViewerBundle\Entity\Index\PerformanceStats;
use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\Output\LogRecordsOutput;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use FD\SymfonyLogViewerBundle\Service\File\LogFileParserInterface;
use FD\SymfonyLogViewerBundle\Service\File\LogFileParserProvider;
use FD\SymfonyLogViewerBundle\Service\File\LogRecordsOutputProvider;
use FD\SymfonyLogViewerBundle\Service\PerformanceService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecordsOutputProvider::class)]
class LogRecordsOutputProviderTest extends TestCase
{
    private LogFileParserInterface&MockObject $logParser;
    private PerformanceService&MockObject $performanceService;
    private LogRecordsOutputProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logParser   = $this->createMock(LogFileParserInterface::class);
        $logParserProvider = $this->createMock(LogFileParserProvider::class);
        $logParserProvider->expects(self::once())->method('get')->with('type')->willReturn($this->logParser);
        $this->performanceService = $this->createMock(PerformanceService::class);
        $this->provider           = new LogRecordsOutputProvider($logParserProvider, $this->performanceService);
    }

    public function testProvide(): void
    {
        $logQuery    = new LogQueryDto('identifier');
        $config      = new LogFilesConfig('name', 'type', 'name', $this->createMock(FinderConfig::class), false, null, '', '');
        $file        = $this->createMock(LogFile::class);
        $logIndex    = $this->createMock(LogIndex::class);
        $performance = new PerformanceStats('1', '2', '3');

        $this->logParser->expects(self::once())->method('getLevels')->willReturn(['level' => 'level']);
        $this->logParser->expects(self::once())->method('getChannels')->willReturn(['channel' => 'channel']);
        $this->logParser->expects(self::once())->method('getLogIndex')->with($config, $file, $logQuery)->willReturn($logIndex);
        $this->performanceService->expects(self::once())->method('getPerformanceStats')->willReturn($performance);

        $expected = new LogRecordsOutput(['level' => 'level'], ['channel' => 'channel'], $logQuery, $logIndex, $performance);

        $result = $this->provider->provide($config, $file, $logQuery);
        static::assertEquals($expected, $result);
    }
}
