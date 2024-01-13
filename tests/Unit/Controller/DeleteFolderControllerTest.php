<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use FD\LogViewer\Controller\DeleteFolderController;
use FD\LogViewer\Entity\LogFolderCollection;
use FD\LogViewer\Service\File\LogFileService;
use FD\LogViewer\Tests\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends AbstractControllerTestCase<DeleteFolderController>
 */
#[CoversClass(DeleteFolderController::class)]
class DeleteFolderControllerTest extends AbstractControllerTestCase
{
    use TestEntityTrait;

    private Filesystem&MockObject $filesystem;
    private LogFileService&MockObject $folderService;

    protected function setUp(): void
    {
        $this->filesystem    = $this->createMock(Filesystem::class);
        $this->folderService = $this->createMock(LogFileService::class);
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

        $this->filesystem->expects(self::never())->method('remove');
        $this->folderService->expects(self::once())->method('findFolderByIdentifier')->with('identifier')->willReturn($logFolder);

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Log folder with id `identifier` is not allowed to be deleted.');
        ($this->controller)('identifier');
    }

    public function testInvokeSuccess(): void
    {
        $config     = $this->createLogFileConfig(['deletable' => true]);
        $collection = new LogFolderCollection($config);
        $logFolder  = $this->createLogFolder(['collection' => $collection]);
        $logFile    = $this->createLogFile(['folder' => $logFolder]);
        $logFolder->addFile($logFile);

        $this->filesystem->expects(self::once())->method('remove')->with('path');
        $this->folderService->expects(self::once())->method('findFolderByIdentifier')->with('identifier')->willReturn($logFolder);

        $expected = new JsonResponse(['success' => true]);
        $response = ($this->controller)('identifier');
        static::assertEquals($expected, $response);
    }

    public function getController(): AbstractController
    {
        return new DeleteFolderController($this->filesystem, $this->folderService);
    }
}
