<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\Http;

use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\AbstractLogFileParser;
use FD\LogViewer\Service\File\Http\HttpAccessFileParser;
use FD\LogViewer\Service\File\Http\HttpAccessLineParser;
use FD\LogViewer\Service\File\LogParser;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

#[CoversClass(HttpAccessFileParser::class)]
#[CoversClass(AbstractLogFileParser::class)]
class HttpAccessFileParserTest extends TestCase
{
    use TestEntityTrait;

    private LogParser&MockObject $logParser;
    private HttpAccessFileParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logParser = $this->createMock(LogParser::class);
        $this->parser    = new HttpAccessFileParser($this->logParser);
    }

    public function testGetChannels(): void
    {
        static::assertSame([], $this->parser->getChannels());
    }

    public function testGetLevels(): void
    {
        static::assertSame([], $this->parser->getLevels());
    }

    public function testGetLogIndex(): void
    {
        $config   = $this->createLogFileConfig();
        $file     = $this->createLogFile();
        $logQuery = $this->createMock(LogQueryDto::class);

        $this->logParser->expects(self::once())->method('parse')
            ->with(
                new SplFileInfo('path'),
                new HttpAccessLineParser('patternB'),
                $logQuery
            );

        $this->parser->getLogIndex($config, $file, $logQuery);
    }
}
