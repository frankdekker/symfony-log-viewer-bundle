<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use FD\SymfonyLogViewerBundle\Controller\LogRecordsController;
use FD\SymfonyLogViewerBundle\Entity\Config\LogFilesConfig;
use FD\SymfonyLogViewerBundle\Entity\LogFile;
use FD\SymfonyLogViewerBundle\Entity\LogFolder;
use FD\SymfonyLogViewerBundle\Entity\LogFolderCollection;
use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Entity\Output\LogRecordsOutput;
use FD\SymfonyLogViewerBundle\Entity\Request\LogQueryDto;
use FD\SymfonyLogViewerBundle\Service\File\LogFileService;
use FD\SymfonyLogViewerBundle\Service\File\LogQueryDtoFactory;
use FD\SymfonyLogViewerBundle\Service\File\LogRecordsOutputProvider;
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

        $config     = $this->createMock(LogFilesConfig::class);
        $collection = new LogFolderCollection($config);
        $logFolder  = new LogFolder('identifier', 'path', 'relative', 11111, 22222, $collection);
        $logFile    = new LogFile('identifier', 'path', 'relative', 11111, 22222, 33333, $logFolder);
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
