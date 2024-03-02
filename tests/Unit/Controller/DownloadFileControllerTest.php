<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use FD\LogViewer\Controller\DownloadFileRemoteHost;
use FD\LogViewer\Entity\LogFolderCollection;
use FD\LogViewer\Service\File\LogFileService;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends AbstractControllerTestCase<DownloadFileRemoteHost>
 */
#[CoversClass(DownloadFileRemoteHost::class)]
class DownloadFileControllerTest extends AbstractControllerTestCase
{
    use TestEntityTrait;

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
        $config     = $this->createLogFileConfig();
        $collection = new LogFolderCollection($config);
        $logFolder  = $this->createLogFolder(['collection' => $collection]);
        $logFile    = $this->createLogFile(['folder' => $logFolder]);

        $this->fileService->expects(self::once())->method('findFileByIdentifier')->with('identifier')->willReturn($logFile);

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Log file with id `identifier` is not downloadable.');
        ($this->controller)('identifier');
    }

    public function testInvokeSuccess(): void
    {
        $config     = $this->createLogFileConfig(['downloadable' => true]);
        $collection = new LogFolderCollection($config);
        $logFolder  = $this->createLogFolder(['collection' => $collection]);
        $logFile    = $this->createLogFile(['path' => __FILE__, 'folder' => $logFolder]);

        $this->fileService->expects(self::once())->method('findFileByIdentifier')->with('identifier')->willReturn($logFile);

        $expected = (new BinaryFileResponse(__FILE__, headers: ['Content-Type' => 'text/plain'], public: false))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'DownloadFileControllerTest.php');

        $response = ($this->controller)('identifier');
        static::assertEquals($expected, $response);
    }

    public function getController(): AbstractController
    {
        return new DownloadFileRemoteHost($this->fileService);
    }
}
