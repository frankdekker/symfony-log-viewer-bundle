<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use FD\LogViewer\Controller\LogRecordsController;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Output\LogRecordsOutput;
use FD\LogViewer\Entity\Request\LogQueryDto;
use FD\LogViewer\Service\File\LogFileService;
use FD\LogViewer\Service\File\LogQueryDtoFactory;
use FD\LogViewer\Service\File\LogRecordsOutputProvider;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends AbstractControllerTestCase<LogRecordsController>
 */
#[CoversClass(LogRecordsController::class)]
class LogRecordsControllerTest extends AbstractControllerTestCase
{
    use TestEntityTrait;

    private LogFileService&MockObject $fileService;
    private LogQueryDtoFactory&MockObject $queryDtoFactory;
    private LogRecordsOutputProvider&MockObject $outputProvider;

    protected function setUp(): void
    {
        $this->fileService     = $this->createMock(LogFileService::class);
        $this->queryDtoFactory = $this->createMock(LogQueryDtoFactory::class);
        $this->outputProvider  = $this->createMock(LogRecordsOutputProvider::class);
        parent::setUp();
    }

    public function testInvokeNotFound(): void
    {
        $request  = new Request();
        $logQuery = new LogQueryDto('file', 123, 'search', DirectionEnum::Asc, ['foo' => 'foo'], ['bar' => 'bar'], 50);

        $this->queryDtoFactory->expects(self::once())->method('create')->with($request)->willReturn($logQuery);
        $this->fileService->expects(self::once())->method('findFileByIdentifier')->with('file')->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Log file with id `file` not found.');
        ($this->controller)($request);
    }

    public function testInvoke(): void
    {
        $request  = new Request();
        $logQuery = new LogQueryDto('file', 123, 'search', DirectionEnum::Asc, ['foo' => 'foo'], ['bar' => 'bar'], 50);

        $logFile    = $this->createLogFile();
        $output     = $this->createMock(LogRecordsOutput::class);

        $this->queryDtoFactory->expects(self::once())->method('create')->with($request)->willReturn($logQuery);
        $this->fileService->expects(self::once())->method('findFileByIdentifier')->with('file')->willReturn($logFile);
        $this->outputProvider->expects(self::once())->method('provide')->with()->willReturn($output);

        $expected = new JsonResponse($output);
        $actual   = ($this->controller)($request);
        static::assertEquals($expected, $actual);
    }

    public function getController(): AbstractController
    {
        return new LogRecordsController($this->fileService, $this->queryDtoFactory, $this->outputProvider);
    }
}
