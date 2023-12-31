<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Service\File\Monolog;

use FD\SymfonyLogViewerBundle\Entity\Index\LogIndex;
use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use FD\SymfonyLogViewerBundle\Service\File\LogFileService;
use FD\SymfonyLogViewerBundle\Service\File\LogParser;
use FD\SymfonyLogViewerBundle\Service\File\Monolog\MonologFileParser;
use Monolog\Logger;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[CoversClass(MonologFileParser::class)]
class MonologFileParserTest extends TestCase
{
    private Logger&MockObject $logger;
    private LogFileService&MockObject $fileService;
    private LogParser&MockObject $logParser;
    private MonologFileParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logger      = $this->createMock(Logger::class);
        $this->fileService = $this->createMock(LogFileService::class);
        $this->logParser   = $this->createMock(LogParser::class);
        $this->parser      = new MonologFileParser([$this->logger], $this->fileService, $this->logParser);
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

    public function testGetLogIndexUnknown(): void
    {
        $query = new LogQueryDto('identifier');

        $this->fileService->expects(self::once())->method('findFileByIdentifier')->with('identifier')->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Log file with id `identifier` not found.');
        $this->parser->getLogIndex($query);
    }

    public function testGetLogIndex(): void
    {
        $query = new LogQueryDto('identifier');
        $file  = new LogFile('identifier', 'path', 'relative', 123, 111, 222);
        $index = new LogIndex();

        $this->fileService->expects(self::once())->method('findFileByIdentifier')->with('identifier')->willReturn($file);
        $this->logParser->expects(self::once())->method('parse')->willReturn($index);

        static::assertSame($index, $this->parser->getLogIndex($query));
    }
}
