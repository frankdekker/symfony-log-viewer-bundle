<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\Nginx;

use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\AbstractLogFileParser;
use FD\LogViewer\Service\File\LogParser;
use FD\LogViewer\Service\File\Nginx\NginxErrorFileParser;
use FD\LogViewer\Service\File\Nginx\NginxErrorLineParser;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

#[CoversClass(NginxErrorFileParser::class)]
#[CoversClass(AbstractLogFileParser::class)]
class NginxFileParserTest extends TestCase
{
    use TestEntityTrait;

    private LogParser&MockObject $logParser;
    private NginxErrorFileParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logParser = $this->createMock(LogParser::class);
        $this->parser    = new NginxErrorFileParser($this->logParser);
    }

    public function testGetLogIndex(): void
    {
        $config   = $this->createLogFileConfig();
        $file     = $this->createLogFile();
        $logQuery = $this->createMock(LogQueryDto::class);

        $this->logParser->expects(self::once())->method('parse')
            ->with(
                new SplFileInfo('path'),
                new NginxErrorLineParser('patternB'),
                $logQuery
            );

        $this->parser->getLogIndex($config, $file, $logQuery);
    }
}
