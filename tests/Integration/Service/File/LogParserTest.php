<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Integration\Service\File;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use FD\SymfonyLogViewerBundle\Iterator\MaxRuntimeIterator;
use FD\SymfonyLogViewerBundle\Service\File\LogParser;
use FD\SymfonyLogViewerBundle\Service\File\Monolog\MonologLineParser;
use FD\SymfonyLogViewerBundle\StreamReader\StreamReaderFactory;
use FD\SymfonyLogViewerBundle\Tests\Integration\AbstractIntegrationTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Bridge\PhpUnit\ClockMock;
use Symfony\Component\Finder\SplFileInfo;

#[CoversClass(LogParser::class)]
class LogParserTest extends AbstractIntegrationTestCase
{
    private MonologLineParser $lineParser;
    private LogParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        ClockMock::register(MaxRuntimeIterator::class);
        $this->lineParser = new MonologLineParser(MonologLineParser::START_OF_MESSAGE_PATTERN, MonologLineParser::LOG_LINE_PATTERN);
        $this->parser     = new LogParser(new StreamReaderFactory());
    }

    public function testParseWithPaginator(): void
    {
        $query = new LogQueryDto('identifier', 0, '', DirectionEnum::Asc, null, null, 5);
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
        $query = new LogQueryDto('identifier', 335, '', DirectionEnum::Asc, null, null, 5);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(5, $index->getLines());
        static::assertNotNull($index->getPaginator());
        static::assertSame(671, $index->getPaginator()->offset);
        static::assertSame('Message for line 6', $index->getLines()[0]->message);
        static::assertSame('Message for line 10', $index->getLines()[4]->message);
    }

    public function testParseAlmostEof(): void
    {
        $query = new LogQueryDto('identifier', 0, '', DirectionEnum::Asc, null, null, 99);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(99, $index->getLines());
        static::assertNotNull($index->getPaginator());
    }

    public function testParseEof(): void
    {
        $query = new LogQueryDto('identifier', 0, '', DirectionEnum::Asc, null, null, 500);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(100, $index->getLines());
        static::assertNull($index->getPaginator());
    }
}
