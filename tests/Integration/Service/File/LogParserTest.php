<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Integration\Service\File;

use DateTimeZone;
use FD\LogViewer\Entity\Expression\Expression;
use FD\LogViewer\Entity\Index\LogRecord;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Reader\Stream\StreamReaderFactory;
use FD\LogViewer\Service\File\LogParser;
use FD\LogViewer\Service\File\Monolog\MonologLineParser;
use FD\LogViewer\Service\Matcher\LogRecordMatcher;
use FD\LogViewer\Tests\Integration\AbstractIntegrationTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Clock\ClockInterface;
use Symfony\Component\Finder\SplFileInfo;

#[CoversClass(LogParser::class)]
class LogParserTest extends AbstractIntegrationTestCase
{
    private MockObject&LogRecordMatcher $logRecordMatcher;
    private MonologLineParser $lineParser;
    private LogParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lineParser       = new MonologLineParser(MonologLineParser::START_OF_MESSAGE_PATTERN, MonologLineParser::LOG_LINE_PATTERN);
        $this->logRecordMatcher = $this->createMock(LogRecordMatcher::class);
        $this->parser           = new LogParser($this->createMock(ClockInterface::class), $this->logRecordMatcher, new StreamReaderFactory());
    }

    public function testParseWithPaginator(): void
    {
        $query = new LogQueryDto(['identifier'], new DateTimeZone('Europe/Amsterdam'), 0, null, DirectionEnum::Asc, 5);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(5, $index->getRecords());
        static::assertNotNull($index->getPaginator());
        static::assertSame(335, $index->getPaginator()->offset);
        static::assertSame('Message for line 1', $index->getRecords()[0]->message);
        static::assertSame('Message for line 5', $index->getRecords()[4]->message);
    }

    public function testParseWithOffset(): void
    {
        $query = new LogQueryDto(['identifier'], new DateTimeZone('Europe/Amsterdam'), 335, null, DirectionEnum::Asc, 5);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(5, $index->getRecords());
        static::assertNotNull($index->getPaginator());
        static::assertSame(671, $index->getPaginator()->offset);
        static::assertSame('Message for line 6', $index->getRecords()[0]->message);
        static::assertSame('Message for line 10', $index->getRecords()[4]->message);
    }

    public function testParseWithExpressionFilter(): void
    {
        $expression = new Expression([]);
        $query      = new LogQueryDto(['identifier'], new DateTimeZone('Europe/Amsterdam'), 0, $expression, DirectionEnum::Asc, 100);
        $file       = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index      = $this->parser->parse($file, $this->lineParser, $query);

        $this->logRecordMatcher->method('matches')->willReturnCallback(fn(LogRecord $record) => $record->severity === 'info');

        static::assertCount(25, $index->getRecords());
        static::assertSame('Message for line 2', $index->getRecords()[0]->message);
        static::assertSame('Message for line 98', $index->getRecords()[24]->message);
    }

    public function testParseAlmostEof(): void
    {
        $query = new LogQueryDto(['identifier'], new DateTimeZone('Europe/Amsterdam'), 0, null, DirectionEnum::Asc, 99);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(99, $index->getRecords());
        static::assertNotNull($index->getPaginator());
    }

    public function testParsePaginatorWithOffset(): void
    {
        $query = new LogQueryDto(['identifier'], new DateTimeZone('Europe/Amsterdam'), 64, null, DirectionEnum::Asc, 500);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(99, $index->getRecords());
        static::assertNotNull($index->getPaginator());
    }

    public function testParseEof(): void
    {
        $query = new LogQueryDto(['identifier'], new DateTimeZone('Europe/Amsterdam'), null, null, DirectionEnum::Asc, 500);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(100, $index->getRecords());
        static::assertNull($index->getPaginator());
    }
}
