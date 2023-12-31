<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Integration\Service\File;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use FD\SymfonyLogViewerBundle\Service\File\LogParser;
use FD\SymfonyLogViewerBundle\Service\File\Monolog\MonologLineParser;
use FD\SymfonyLogViewerBundle\StreamReader\StreamReaderFactory;
use FD\SymfonyLogViewerBundle\Tests\Integration\AbstractIntegrationTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Finder\SplFileInfo;

#[CoversClass(LogParser::class)]
class LogParserTest extends AbstractIntegrationTestCase
{
    private MonologLineParser $lineParser;
    private LogParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->lineParser = new MonologLineParser();
        $this->parser     = new LogParser(new StreamReaderFactory());
    }

    public function testParseWithPaginator(): void
    {
        $query = new LogQueryDto('identifier', 0, '', DirectionEnum::Asc, null, null, 25);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(25, $index->getLines());
        static::assertNotNull($index->getPaginator());
        static::assertSame(1768, $index->getPaginator()->offset);
        static::assertSame('Message for line 1', $index->getLines()[0]->message);
        static::assertSame('Message for line 25', $index->getLines()[24]->message);
    }

    public function testParseWithOffset(): void
    {
        $query = new LogQueryDto('identifier', 1768, '', DirectionEnum::Asc, null, null, 25);
        $file  = new SplFileInfo($this->getResourcePath('Integration/Service/LogParser/monolog.log'), '', '');
        $index = $this->parser->parse($file, $this->lineParser, $query);

        static::assertCount(25, $index->getLines());
        static::assertNotNull($index->getPaginator());
        static::assertSame(3550, $index->getPaginator()->offset);
        static::assertSame('Message for line 26', $index->getLines()[0]->message);
        static::assertSame('Message for line 50', $index->getLines()[24]->message);
    }
}
