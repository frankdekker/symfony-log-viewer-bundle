<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File;

use ArrayIterator;
use Exception;
use FD\LogViewer\Entity\Index\LogRecordCollection;
use FD\LogViewer\Entity\Index\PerformanceStats;
use FD\LogViewer\Entity\Output\LogRecordsOutput;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Iterator\DeduplicationIterator;
use FD\LogViewer\Iterator\LimitIterator;
use FD\LogViewer\Iterator\MultiLogRecordIterator;
use FD\LogViewer\Service\File\LogFileParserInterface;
use FD\LogViewer\Service\File\LogFileParserProvider;
use FD\LogViewer\Service\File\LogRecordDateComparator;
use FD\LogViewer\Service\File\LogRecordsOutputProvider;
use FD\LogViewer\Service\PerformanceService;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecordsOutputProvider::class)]
class LogRecordsOutputProviderTest extends TestCase
{
    use TestEntityTrait;

    private LogFileParserInterface&MockObject $logParser;
    private PerformanceService&MockObject $performanceService;
    private LogRecordsOutputProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logParser   = $this->createMock(LogFileParserInterface::class);
        $logParserProvider = $this->createMock(LogFileParserProvider::class);
        $logParserProvider->expects(self::once())->method('get')->with('monolog')->willReturn($this->logParser);
        $this->performanceService = $this->createMock(PerformanceService::class);
        $this->provider           = new LogRecordsOutputProvider($logParserProvider, $this->performanceService);
    }

    public function testProvide(): void
    {
        $logQuery         = new LogQueryDto(['identifier']);
        $file             = $this->createLogFile();
        $config           = $file->folder->collection->config;
        $recordCollection = $this->createMock(LogRecordCollection::class);
        $performance      = new PerformanceStats('1', '2', '3');

        $this->logParser->expects(self::once())->method('getLogIndex')->with($config, $file, $logQuery)->willReturn($recordCollection);
        $this->performanceService->expects(self::once())->method('getPerformanceStats')->willReturn($performance);

        $expected = new LogRecordsOutput($recordCollection, $performance);

        $result = $this->provider->provide($file, $logQuery);
        static::assertEquals($expected, $result);
    }

    /**
     * @throws Exception
     */
    public function testProvideForFiles(): void
    {
        $logQuery         = new LogQueryDto(['identifier']);
        $file             = $this->createLogFile();
        $config           = $file->folder->collection->config;
        $recordCollection = $this->createMock(LogRecordCollection::class);
        $iterator         = new ArrayIterator([]);
        $performance      = new PerformanceStats('1', '2', '3');

        $this->logParser->expects(self::once())->method('getLogIndex')->with($config, $file, $logQuery)->willReturn($recordCollection);
        $recordCollection->expects(self::once())->method('getIterator')->willReturn($iterator);
        $this->performanceService->expects(self::once())->method('getPerformanceStats')->willReturn($performance);

        $expected = new LogRecordsOutput(
            new LogRecordCollection(
                new LimitIterator(
                    new DeduplicationIterator(new MultiLogRecordIterator([$iterator], new LogRecordDateComparator($logQuery->direction))),
                    $logQuery->perPage
                ),
                null
            ),
            $performance
        );

        $result = $this->provider->provideForFiles(['foo' => $file], $logQuery);
        static::assertEquals($expected, $result);
    }
}
