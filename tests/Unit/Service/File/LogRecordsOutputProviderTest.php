<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File;

use ArrayIterator;
use DateTimeZone;
use Exception;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Entity\Index\LogRecordCollection;
use FD\LogViewer\Entity\Index\PerformanceStats;
use FD\LogViewer\Entity\Output\LogRecordsOutput;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\LogFileParserInterface;
use FD\LogViewer\Service\File\LogFileParserProvider;
use FD\LogViewer\Service\File\LogRecordsOutputProvider;
use FD\LogViewer\Service\PerformanceService;
use FD\LogViewer\Service\Serializer\LogRecordsNormalizer;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(LogRecordsOutputProvider::class)]
class LogRecordsOutputProviderTest extends TestCase
{
    use TestEntityTrait;

    private LogFileParserInterface&MockObject $logParser;
    private LogRecordsNormalizer&MockObject $logRecordsNormalizer;
    private PerformanceService&MockObject $performanceService;
    private LogRecordsOutputProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logParser   = $this->createMock(LogFileParserInterface::class);
        $logParserProvider = $this->createMock(LogFileParserProvider::class);
        $logParserProvider->expects(self::once())->method('get')->with('monolog')->willReturn($this->logParser);
        $this->logRecordsNormalizer = $this->createMock(LogRecordsNormalizer::class);
        $this->performanceService   = $this->createMock(PerformanceService::class);
        $this->provider             = new LogRecordsOutputProvider($logParserProvider, $this->logRecordsNormalizer, $this->performanceService);
    }

    public function testProvide(): void
    {
        $logQuery         = new LogQueryDto(['identifier'], new DateTimeZone('Europe/Amsterdam'));
        $file             = $this->createLogFile();
        $config           = $file->folder->collection->config;
        $record           = new LogRecord('id', 111111, 'debug', 'request', 'message', [], []);
        $recordCollection = $this->createMock(LogRecordCollection::class);
        $recordCollection->method('getRecords')->willReturn([$record]);
        $recordCollection->method('getPaginator')->willReturn(null);
        $performance = new PerformanceStats('1', '2', '3');

        $this->logParser->expects(self::once())->method('getLogIndex')->with($config, $file, $logQuery)->willReturn($recordCollection);
        $this->logRecordsNormalizer->expects(self::once())->method('normalize')
            ->with([$record], new DateTimeZone('Europe/Amsterdam'))
            ->willReturn(['records']);
        $this->performanceService->expects(self::once())->method('getPerformanceStats')->willReturn($performance);

        $expected = new LogRecordsOutput(['records'], null, $performance);

        $result = $this->provider->provide($file, $logQuery);
        static::assertEquals($expected, $result);
    }

    /**
     * @throws Exception
     */
    public function testProvideForFiles(): void
    {
        $logQuery         = new LogQueryDto(['identifier'], new DateTimeZone('Europe/Amsterdam'));
        $file             = $this->createLogFile();
        $config           = $file->folder->collection->config;
        $record           = new LogRecord('id', 111111, 'debug', 'request', 'message', [], []);
        $recordCollection = $this->createMock(LogRecordCollection::class);
        $recordCollection->method('getIterator')->willReturn(new ArrayIterator([$record]));
        $recordCollection->method('getPaginator')->willReturn(null);
        $performance = new PerformanceStats('1', '2', '3');

        $this->logParser->expects(self::once())->method('getLogIndex')->with($config, $file, $logQuery)->willReturn($recordCollection);
        $this->logRecordsNormalizer->expects(self::once())->method('normalize')
            ->with([$record], new DateTimeZone('Europe/Amsterdam'))
            ->willReturn(['records']);
        $this->performanceService->expects(self::once())->method('getPerformanceStats')->willReturn($performance);

        $expected = new LogRecordsOutput(['records'], null, $performance);

        $result = $this->provider->provideForFiles(['foo' => $file], $logQuery);
        static::assertEquals($expected, $result);
    }
}
