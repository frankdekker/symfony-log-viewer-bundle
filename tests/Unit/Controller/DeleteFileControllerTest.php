<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use FD\LogViewer\Controller\DeleteFileController;
use FD\LogViewer\Entity\LogFolderCollection;
use FD\LogViewer\Service\File\LogFileService;
use FD\LogViewer\Tests\Utility\TestEntityTrait;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @extends AbstractControllerTestCase<DeleteFileController>
 */
#[CoversClass(DeleteFileController::class)]
class DeleteFileControllerTest extends AbstractControllerTestCase
{
    use TestEntityTrait;

    private Filesystem&MockObject $filesystem;
    private LogFileService&MockObject $fileService;

    protected function setUp(): void
    {
        $this->filesystem  = $this->createMock(Filesystem::class);
        $this->fileService = $this->createMock(LogFileService::class);
        parent::setUp();
    }

    public function testInvokeNotFound(): void
    {
        $this->filesystem->expects(self::never())->method('remove');
        $this->fileService->expects(self::once())->method('findFileByIdentifier')->with('identifier')->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->expectExceptionMessage('Log file with id `identifier` not found.');
        ($this->controller)('identifier');
    }

    public function testInvokeNotDeletable(): void
    {
        $config     = $this->createLogFileConfig();
        $collection = new LogFolderCollection($config);
        $logFolder  = $this->createLogFolder(['collection' => $collection]);
        $logFile    = $this->createLogFile(['folder' => $logFolder]);

        $this->filesystem->expects(self::never())->method('remove');
        $this->fileService->expects(self::once())->method('findFileByIdentifier')->with('identifier')->willReturn($logFile);

        $this->expectException(AccessDeniedHttpException::class);
        $this->expectExceptionMessage('Log file with id `identifier` is not allowed to be deleted.');
        ($this->controller)('identifier');
    }

    public function testInvokeSuccess(): void
    {
        $config     = $this->createLogFileConfig(['deletable' => true]);
        $collection = new LogFolderCollection($config);
        $logFolder  = $this->createLogFolder(['collection' => $collection]);
        $logFile    = $this->createLogFile(['folder' => $logFolder]);

        $this->filesystem->expects(self::once())->method('remove')->with('path');
        $this->fileService->expects(self::once())->method('findFileByIdentifier')->with('identifier')->willReturn($logFile);

        $response = ($this->controller)('identifier');
        static::assertEquals('{"success":true}', $response->getContent());
    }

    public function getController(): AbstractController
    {
        return new DeleteFileController($this->filesystem, $this->fileService);
    }
}
