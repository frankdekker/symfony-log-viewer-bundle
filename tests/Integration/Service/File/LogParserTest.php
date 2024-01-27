<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Integration\Service\File;

use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Reader\Stream\StreamReaderFactory;
use FD\LogViewer\Service\File\LogParser;
use FD\LogViewer\Service\File\Monolog\MonologLineParser;
use FD\LogViewer\Service\Matcher\LogRecordMatcher;
use FD\LogViewer\Tests\Integration\AbstractIntegrationTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Psr\Clock\ClockInterface;
use Symfony\Component\Finder\SplFileInfo;

#[CoversClass(LogParser::class)]
class LogParserTest extends AbstractIntegrationTestCase
{
    private MonologLineParser $lineParser;
    private LogParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lineParser = new MonologLineParser(MonologLineParser::START_OF_MESSAGE_PATTERN, MonologLineParser::LOG_LINE_PATTERN);
        $logRecordMatcher = $this->createMock(LogRecordMatcher::class);
        $this->parser     = new LogParser($this->createMock(ClockInterface::class), $logRecordMatcher, new StreamReaderFactory());
    }

    public function testParseWithPaginator(): void
    {
        $query = new LogQueryDto('identifier', 0, null, DirectionEnum::Asc, null, null, 5);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(5, $index->getLines());
        static::assertNotNull($index->getPaginator());
        static::assertSame(335, $index->getPaginator()->offset);
        static::assertSame('Message for line 1', $index->getLines()[0]->message);
        static::assertSame('Message for line 5', $index->getLines()[4]->message);
    }

    public function testParseWithOffset(): void
    {
        $query = new LogQueryDto('identifier', 335, null, DirectionEnum::Asc, null, null, 5);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(5, $index->getLines());
        static::assertNotNull($index->getPaginator());
        static::assertSame(671, $index->getPaginator()->offset);
        static::assertSame('Message for line 6', $index->getLines()[0]->message);
        static::assertSame('Message for line 10', $index->getLines()[4]->message);
    }

    public function testParseWithLevelFilter(): void
    {
        $query = new LogQueryDto('identifier', 0, null, DirectionEnum::Asc, ['info'], null, 100);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(25, $index->getLines());
        static::assertSame('Message for line 2', $index->getLines()[0]->message);
        static::assertSame('Message for line 98', $index->getLines()[24]->message);
    }

    public function testParseWithChannelFilter(): void
    {
        $query = new LogQueryDto('identifier', 0, null, DirectionEnum::Asc, null, ['app'], 100);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(34, $index->getLines());
        static::assertSame('Message for line 1', $index->getLines()[0]->message);
        static::assertSame('Message for line 100', $index->getLines()[33]->message);
    }

    public function testParseWithLevelAndChannelFilter(): void
    {
        $query = new LogQueryDto('identifier', 0, null, DirectionEnum::Asc, ['info'], ['app'], 100);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(8, $index->getLines());
        static::assertSame('Message for line 10', $index->getLines()[0]->message);
        static::assertSame('Message for line 94', $index->getLines()[7]->message);
    }

    public function testParseAlmostEof(): void
    {
        $query = new LogQueryDto('identifier', 0, null, DirectionEnum::Asc, null, null, 99);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(99, $index->getLines());
        static::assertNotNull($index->getPaginator());
    }

    public function testParsePaginatorWithOffset(): void
    {
        $query = new LogQueryDto('identifier', 64, null, DirectionEnum::Asc, null, null, 500);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(99, $index->getLines());
        static::assertNotNull($index->getPaginator());
    }

    public function testParseEof(): void
    {
        $query = new LogQueryDto('identifier', null, null, DirectionEnum::Asc, null, null, 500);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(100, $index->getLines());
        static::assertNull($index->getPaginator());
    }
}
