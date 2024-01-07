<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Tests\Unit\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use FD\SymfonyLogViewerBundle\Controller\DownloadFolderController;
use FD\SymfonyLogViewerBundle\Entity\Config\FinderConfig;
use FD\SymfonyLogViewerBundle\Entity\Config\LogFilesConfig;
use FD\SymfonyLogViewerBundle\Entity\LogFolder;
use FD\SymfonyLogViewerBundle\Entity\LogFolderCollection;
use FD\SymfonyLogViewerBundle\Service\File\LogFileService;
use FD\SymfonyLogViewerBundle\Service\Folder\ZipArchiveFactory;
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
        $config     = new LogFilesConfig('name', 'type', 'name', $this->createMock(FinderConfig::class), false, null, '', '');
        $collection = new LogFolderCollection($config);
        $logFolder  = new LogFolder('identifier', 'path', 'relative', 11111, 22222, $collection);

        $this->folderService->expects(self::once())->method('findFolderByIdentifier')->with('identifier')->willReturn($logFolder);

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Log folder with id `identifier` is not downloadable.');
        ($this->controller)('identifier');
    }

    public function testInvokeSuccess(): void
    {
        $config     = new LogFilesConfig('name', 'type', 'name', $this->createMock(FinderConfig::class), true, null, '', '');
        $collection = new LogFolderCollection($config);
        $logFolder  = new LogFolder('identifier', 'path', 'relative', 11111, 22222, $collection);
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
