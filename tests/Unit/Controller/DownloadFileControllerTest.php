<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use FD\LogViewer\Controller\DownloadFileController;
use FD\LogViewer\Entity\Config\FinderConfig;
use FD\LogViewer\Entity\Config\LogFilesConfig;
use FD\LogViewer\Entity\LogFile;
use FD\LogViewer\Entity\LogFolder;
use FD\LogViewer\Entity\LogFolderCollection;
use FD\LogViewer\Service\File\LogFileService;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends AbstractControllerTestCase<DownloadFileController>
 */
#[CoversClass(DownloadFileController::class)]
class DownloadFileControllerTest extends AbstractControllerTestCase
{
    private LogFileService&MockObject $fileService;

    protected function setUp(): void
    {
        $this->fileService = $this->createMock(LogFileService::class);
        parent::setUp();
    }

    public function testInvokeNotFound(): void
    {
        $this->fileService->expects(self::once())->method('findFileByIdentifier')->with('identifier')->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Log file with id `identifier` not found.');
        ($this->controller)('identifier');
    }

    public function testInvokeNotDownloadable(): void
    {
        $config     = new LogFilesConfig('name', 'type', 'name', $this->createMock(FinderConfig::class), false, null, '', '');
        $collection = new LogFolderCollection($config);
        $logFolder  = new LogFolder('identifier', 'path', 'relative', 11111, 22222, $collection);
        $logFile    = new LogFile('identifier', 'path', 'relative', 11111, 22222, 33333, $logFolder);

        $this->fileService->expects(self::once())->method('findFileByIdentifier')->with('identifier')->willReturn($logFile);

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Log file with id `identifier` is not downloadable.');
        ($this->controller)('identifier');
    }

    public function testInvokeSuccess(): void
    {
        $config     = new LogFilesConfig('name', 'type', 'name', $this->createMock(FinderConfig::class), true, null, '', '');
        $collection = new LogFolderCollection($config);
        $logFolder  = new LogFolder('identifier', 'path', 'relative', 11111, 22222, $collection);
        $logFile    = new LogFile('identifier', __FILE__, 'relative', 11111, 22222, 33333, $logFolder);

        $this->fileService->expects(self::once())->method('findFileByIdentifier')->with('identifier')->willReturn($logFile);

        $expected = (new BinaryFileResponse(__FILE__, headers: ['Content-Type' => 'text/plain'], public: false))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'DownloadFileControllerTest.php');

        $response = ($this->controller)('identifier');
        static::assertEquals($expected, $response);
    }

    public function getController(): AbstractController
    {
        return new DownloadFileController($this->fileService);
    }
}
