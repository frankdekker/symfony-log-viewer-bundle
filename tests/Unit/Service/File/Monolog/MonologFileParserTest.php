<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\Monolog;

use FD\LogViewer\Entity\Index\LogIndex;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\LogFolder;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\LogParser;
use FD\LogViewer\Service\File\Monolog\MonologFileParser;
use FD\LogViewer\Service\File\Monolog\MonologLineParser;
use FD\LogViewer\Tests\TestEntityTrait;
use Monolog\Logger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

#[CoversClass(MonologFileParser::class)]
class MonologFileParserTest extends TestCase
{
    use TestEntityTrait;

    private Logger&MockObject $logger;
    private LogParser&MockObject $logParser;
    private MonologFileParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logger    = $this->createMock(Logger::class);
        $this->logParser = $this->createMock(LogParser::class);
        $this->parser    = new MonologFileParser([$this->logger], $this->logParser);
    }

    public function testGetLevels(): void
    {
        $expected = [
            'emergency' => 'Emergency',
            'alert'     => 'Alert',
            'critical'  => 'Critical',
            'error'     => 'Error',
            'warning'   => 'Warning',
            'notice'    => 'Notice',
            'info'      => 'Info',
            'debug'     => 'Debug'
        ];
        static::assertSame($expected, $this->parser->getLevels());
    }

    public function testGetChannels(): void
    {
        $this->logger->expects(self::exactly(2))->method('getName')->willReturn('app');
        static::assertSame(['app' => 'app'], $this->parser->getChannels());
    }

    public function testGetLogIndex(): void
    {
        $config   = $this->createLogFileConfig();
        $logQuery = new LogQueryDto('identifier');
        $file     = new LogFile('identifier', 'path', 'relative', 123, 111, 222, $this->createMock(LogFolder::class));
        $index    = new LogIndex();

        $this->logParser->expects(self::once())
            ->method('parse')
            ->with(new SplFileInfo('path'), new MonologLineParser('patternA', 'patternB'), $logQuery)
            ->willReturn($index);

        static::assertSame($index, $this->parser->getLogIndex($config, $file, $logQuery));
    }
}
