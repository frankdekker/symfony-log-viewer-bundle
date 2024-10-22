<?php

declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\ErrorLog;

use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\ErrorLog\PhpErrorLogFileParser;
use FD\LogViewer\Service\File\ErrorLog\PhpErrorLogLineParser;
use FD\LogViewer\Service\File\LogParser;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

#[CoversClass(PhpErrorLogFileParser::class)]
class PhpErrorLogFileParserTest extends TestCase
{
    use TestEntityTrait;

    private LogParser&MockObject $logParser;
    private PhpErrorLogFileParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logParser = $this->createMock(LogParser::class);
        $this->parser    = new PhpErrorLogFileParser($this->logParser);
    }

    public function testGetLogIndex(): void
    {
        $config   = $this->createLogFileConfig();
        $file     = $this->createLogFile();
        $logQuery = $this->createMock(LogQueryDto::class);

        $this->logParser->expects(self::once())->method('parse')
            ->with(
                new SplFileInfo('path'),
                new PhpErrorLogLineParser('patternB'),
                $config,
                $logQuery
            );

        $this->parser->getLogIndex($config, $file, $logQuery);
    }
}
