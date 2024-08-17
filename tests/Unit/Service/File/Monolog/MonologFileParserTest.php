<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Service\File\Monolog;

use ArrayIterator;
use DateTimeZone;
use FD\LogViewer\Entity\Index\LogRecordCollection;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\LogParser;
use FD\LogViewer\Service\File\Monolog\MonologFileParser;
use FD\LogViewer\Service\File\Monolog\MonologJsonParser;
use FD\LogViewer\Service\File\Monolog\MonologLineParser;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

#[CoversClass(MonologFileParser::class)]
class MonologFileParserTest extends TestCase
{
    use TestEntityTrait;

    private LogParser&MockObject $logParser;
    private MonologFileParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logParser = $this->createMock(LogParser::class);
        $this->parser    = new MonologFileParser(MonologFileParser::TYPE_LINE, $this->logParser);
    }

    public function testGetLogIndexForLineParser(): void
    {
        $config   = $this->createLogFileConfig();
        $logQuery = new LogQueryDto(['identifier'], new DateTimeZone('Europe/Amsterdam'));
        $file     = $this->createLogFile();
        $index    = new LogRecordCollection(new ArrayIterator([]), null);

        $this->logParser->expects(self::once())
            ->method('parse')
            ->with(new SplFileInfo('path'), new MonologLineParser('patternA', 'patternB'), $config, $logQuery)
            ->willReturn($index);

        static::assertSame($index, $this->parser->getLogIndex($config, $file, $logQuery));
    }

    public function testGetLogIndexForJsonParser(): void
    {
        $config           = $this->createLogFileConfig();
        $logQuery         = new LogQueryDto(['identifier'], new DateTimeZone('Europe/Amsterdam'));
        $file             = $this->createLogFile();
        $recordCollection = new LogRecordCollection(new ArrayIterator([]), null);

        $this->logParser->expects(self::once())
            ->method('parse')
            ->with(new SplFileInfo('path'), new MonologJsonParser(), $config, $logQuery)
            ->willReturn($recordCollection);

        $parser = new MonologFileParser(MonologFileParser::TYPE_JSON, $this->logParser);
        static::assertSame($recordCollection, $parser->getLogIndex($config, $file, $logQuery));
    }

    public function testGetLogIndexInvalidType(): void
    {
        $config = $this->createLogFileConfig();
        $file   = $this->createLogFile();
        // @phpstan-ignore-next-line
        $parser = new MonologFileParser('foobar', $this->logParser);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid format type');
        $parser->getLogIndex($config, $file, new LogQueryDto(['identifier'], new DateTimeZone('Europe/Amsterdam')));
    }
}
