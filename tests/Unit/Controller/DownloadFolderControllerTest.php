<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use FD\LogViewer\Controller\DownloadFolderController;
use FD\LogViewer\Entity\LogFolderCollection;
use FD\LogViewer\Service\File\LogFileService;
use FD\LogViewer\Service\Folder\ZipArchiveFactory;
use FD\LogViewer\Tests\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use SplFileInfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends AbstractControllerTestCase<DownloadFolderController>
 */
#[CoversClass(DownloadFolderController::class)]
class DownloadFolderControllerTest extends AbstractControllerTestCase
{
    use TestEntityTrait;

    private LogFileService&MockObject $folderService;
    private ZipArchiveFactory&MockObject $zipArchiveFactory;

    protected function setUp(): void
    {
        $this->folderService     = $this->createMock(LogFileService::class);
        $this->zipArchiveFactory = $this->createMock(ZipArchiveFactory::class);
        parent::setUp();
    }

    public function testInvokeNotFound(): void
    {
        $this->folderService->expects(self::once())->method('findFolderByIdentifier')->with('identifier')->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Log folder with id `identifier` not found.');
        ($this->controller)('identifier');
    }

    public function testInvokeNotDownloadable(): void
    {
        $config     = $this->createLogFileConfig();
        $collection = new LogFolderCollection($config);
        $logFolder  = $this->createLogFolder(['collection' => $collection]);

        $this->folderService->expects(self::once())->method('findFolderByIdentifier')->with('identifier')->willReturn($logFolder);

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Log folder with id `identifier` is not downloadable.');
        ($this->controller)('identifier');
    }

    public function testInvokeSuccess(): void
    {
        $config     = $this->createLogFileConfig(['downloadable' => true]);
        $collection = new LogFolderCollection($config);
        $logFolder  = $this->createLogFolder(['collection' => $collection]);
        $zipFile    = new SplFileInfo(__FILE__);

        $this->folderService->expects(self::once())->method('findFolderByIdentifier')->with('identifier')->willReturn($logFolder);
        $this->zipArchiveFactory->expects(self::once())->method('createFromFolder')->with($logFolder)->willReturn($zipFile);

        $expected = (new BinaryFileResponse(__FILE__, headers: ['Content-Type' => 'application/zip'], public: false))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'path.zip');

        $response = ($this->controller)('identifier');
        static::assertEquals($expected, $response);
    }

    public function getController(): AbstractController
    {
        return new DownloadFolderController($this->folderService, $this->zipArchiveFactory);
    }
}
