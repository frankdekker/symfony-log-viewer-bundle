<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use FD\SymfonyLogViewerBundle\Controller\LogRecordsController;
use FD\SymfonyLogViewerBundle\Entity\Index\LogIndex;
use FD\SymfonyLogViewerBundle\Entity\Index\PerformanceStats;
use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Entity\Output\LogRecordsOutput;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use FD\SymfonyLogViewerBundle\Service\File\LogQueryDtoFactory;
use FD\SymfonyLogViewerBundle\Service\File\Monolog\MonologFileParser;
use FD\SymfonyLogViewerBundle\Service\PerformanceService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends AbstractControllerTestCase<LogRecordsController>
 */
#[CoversClass(LogRecordsController::class)]
class LogRecordsControllerTest extends AbstractControllerTestCase
{
    private LogQueryDtoFactory&MockObject $queryDtoFactory;
    private MonologFileParser&MockObject $logParser;
    private PerformanceService&MockObject $performanceService;

    protected function setUp(): void
    {
        $this->queryDtoFactory    = $this->createMock(LogQueryDtoFactory::class);
        $this->logParser          = $this->createMock(MonologFileParser::class);
        $this->performanceService = $this->createMock(PerformanceService::class);
        parent::setUp();
    }

    public function testInvoke(): void
    {
        $request     = new Request();
        $logQuery    = new LogQueryDto('file', 123, 'search', DirectionEnum::Asc, ['foo' => 'foo'], ['bar' => 'bar'], 50);
        $logIndex    = new LogIndex();
        $performance = new PerformanceStats('foo', 'bar', '1.0.0');

        $this->logParser->expects(self::once())->method('getLevels')->willReturn(['debug' => 'debug']);
        $this->logParser->expects(self::once())->method('getChannels')->willReturn(['request' => 'request']);
        $this->queryDtoFactory->expects(self::once())
            ->method('create')
            ->with($request, ['debug' => 'debug'], ['request' => 'request'])
            ->willReturn($logQuery);
        $this->logParser->expects(self::once())->method('getLogIndex')->with($logQuery)->willReturn($logIndex);
        $this->performanceService->expects(self::once())->method('getPerformanceStats')->with($request)->willReturn($performance);

        $expected = new JsonResponse(
            new LogRecordsOutput(['debug' => 'debug'], ['request' => 'request'], $logQuery, $logIndex, $performance)
        );
        static::assertEquals($expected, ($this->controller)($request));
    }

    public function getController(): AbstractController
    {
        return new LogRecordsController($this->queryDtoFactory, $this->logParser, $this->performanceService);
    }
}
