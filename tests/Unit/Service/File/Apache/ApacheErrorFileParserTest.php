<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\Apache;

use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\Apache\ApacheErrorFileParser;
use FD\LogViewer\Service\File\Apache\ApacheErrorLineParser;
use FD\LogViewer\Service\File\LogParser;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

#[CoversClass(ApacheErrorFileParser::class)]
class ApacheErrorFileParserTest extends TestCase
{
    use TestEntityTrait;

    private LogParser&MockObject $logParser;
    private ApacheErrorFileParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logParser = $this->createMock(LogParser::class);
        $this->parser    = new ApacheErrorFileParser($this->logParser);
    }

    public function testGetLogIndex(): void
    {
        $config   = $this->createLogFileConfig();
        $file     = $this->createLogFile();
        $logQuery = $this->createMock(LogQueryDto::class);

        $this->logParser->expects(self::once())->method('parse')
            ->with(
                new SplFileInfo('path'),
                new ApacheErrorLineParser('patternB'),
                $config,
                $logQuery
            );

        $this->parser->getLogIndex($config, $file, $logQuery);
    }
}
