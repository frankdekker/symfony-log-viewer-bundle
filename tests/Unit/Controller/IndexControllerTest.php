<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use FD\LogViewer\Controller\IndexController;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Output\LogFolderOutput;
use FD\LogViewer\Routing\RouteService;
use FD\LogViewer\Service\Folder\LogFolderOutputProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Throwable;

/**
 * @extends AbstractControllerTestCase<IndexController>
 */
#[CoversClass(IndexController::class)]
class IndexControllerTest extends AbstractControllerTestCase
{
    private RouteService&MockObject $routeService;
    private LogFolderOutputProvider&MockObject $folderOutputProvider;

    protected function setUp(): void
    {
        $this->routeService         = $this->createMock(RouteService::class);
        $this->folderOutputProvider = $this->createMock(LogFolderOutputProvider::class);
        parent::setUp();
    }

    /**
     * @throws Throwable
     */
    public function testInvoke(): void
    {
        $folder = new LogFolderOutput('identifier', 'path', 'url', true, 123456, []);

        $this->routeService->expects(self::once())->method('getBaseUri')->willReturn('baseUri');
        $this->folderOutputProvider->expects(self::once())->method('provide')->with(DirectionEnum::Desc)->willReturn([$folder]);

        $this->expectRender('@SymfonyLogViewer/index.html.twig', ['base_uri' => 'baseUri', 'folders' => [$folder]]);

        ($this->controller)();
    }

    public function getController(): AbstractController
    {
        return new IndexController($this->routeService, $this->folderOutputProvider);
    }
}
