<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\Nginx;

use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\Http\HttpAccessLineParser;
use FD\LogViewer\Service\File\LogParser;
use FD\LogViewer\Service\File\Nginx\ApacheErrorLineParser;
use FD\LogViewer\Service\File\Nginx\NginxErrorFileParser;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

#[CoversClass(NginxErrorFileParser::class)]
class NginxFileParserTest extends TestCase
{
    use TestEntityTrait;

    private LogParser&MockObject $logParser;
    private NginxErrorFileParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logParser = $this->createMock(LogParser::class);
        $this->parser    = new NginxErrorFileParser('access', $this->logParser);
    }

    public function testGetChannels(): void
    {
        static::assertSame([], $this->parser->getChannels());
    }

    public function testGetLevels(): void
    {
        static::assertSame([], $this->parser->getLevels());
    }

    public function testGetLogIndexForAccessLog(): void
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

    public function testGetLogIndexForErrorLog(): void
    {
        $config   = $this->createLogFileConfig();
        $file     = $this->createLogFile();
        $logQuery = $this->createMock(LogQueryDto::class);

        $this->logParser->expects(self::once())->method('parse')
            ->with(
                new SplFileInfo('path'),
                new ApacheErrorLineParser('patternB'),
                $logQuery
            );

        $this->parser = new NginxErrorFileParser('error', $this->logParser);
        $this->parser->getLogIndex($config, $file, $logQuery);
    }

    public function testGetLogIndexInvalidType(): void
    {
        $config   = $this->createLogFileConfig();
        $file     = $this->createLogFile();
        $logQuery = $this->createMock(LogQueryDto::class);

        $this->parser = new NginxErrorFileParser('foobar', $this->logParser);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Unknown log type:');
        $this->parser->getLogIndex($config, $file, $logQuery);
    }
}
