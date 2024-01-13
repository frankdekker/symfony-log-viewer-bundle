<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use FD\LogViewer\Controller\FoldersController;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Output\LogFolderOutput;
use FD\LogViewer\Service\Folder\LogFolderOutputProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends AbstractControllerTestCase<FoldersController>
 */
#[CoversClass(FoldersController::class)]
class FoldersControllerTest extends AbstractControllerTestCase
{
    private LogFolderOutputProvider&MockObject $folderOutputProvider;

    protected function setUp(): void
    {
        $this->folderOutputProvider = $this->createMock(LogFolderOutputProvider::class);
        parent::setUp();
    }

    public function testInvoke(): void
    {
        $request = new Request();
        $folder  = new LogFolderOutput('identifier', 'path', true, false, 123456, []);

        $this->folderOutputProvider->expects(self::once())->method('provide')->with(DirectionEnum::Desc)->willReturn([$folder]);

        ($this->controller)($request);
    }

    public function testInvokeWithQueryParam(): void
    {
        $request = new Request(['direction' => DirectionEnum::Asc->value]);
        $folder  = new LogFolderOutput('identifier', 'path', true, false, 123456, []);

        $this->folderOutputProvider->expects(self::once())->method('provide')->with(DirectionEnum::Asc)->willReturn([$folder]);

        ($this->controller)($request);
    }

    public function getController(): AbstractController
    {
        return new FoldersController($this->folderOutputProvider);
    }
}
