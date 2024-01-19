<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use FD\LogViewer\Controller\IndexController;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Output\LogFolderOutput;
use FD\LogViewer\Routing\RouteService;
use FD\LogViewer\Service\Folder\LogFolderOutputProvider;
use FD\LogViewer\Service\JsonManifestAssetLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Throwable;

use function DR\PHPUnitExtensions\Mock\consecutive;

/**
 * @extends AbstractControllerTestCase<IndexController>
 */
#[CoversClass(IndexController::class)]
class IndexControllerTest extends AbstractControllerTestCase
{
    private JsonManifestAssetLoader&MockObject $assetLoader;
    private RouteService&MockObject $routeService;
    private LogFolderOutputProvider&MockObject $folderOutputProvider;

    protected function setUp(): void
    {
        $this->assetLoader          = $this->createMock(JsonManifestAssetLoader::class);
        $this->routeService         = $this->createMock(RouteService::class);
        $this->folderOutputProvider = $this->createMock(LogFolderOutputProvider::class);
        parent::setUp();
    }

    /**
     * @throws Throwable
     */
    public function testInvoke(): void
    {
        $folder = new LogFolderOutput('identifier', 'path', true, false, 123456, []);

        $this->routeService->expects(self::once())->method('getBaseUri')->willReturn('baseUri');
        $this->folderOutputProvider->expects(self::once())->method('provide')->with(DirectionEnum::Desc)->willReturn([$folder]);
        $this->assetLoader->expects(self::exactly(2))
            ->method('getUrl')
            ->with(...consecutive(['style.css'], ['src/main.ts']))
            ->willReturn('url1', 'url2');

        $this->expectRender(
            '@FdLogViewer/index.html.twig',
            [
                'base_uri' => 'baseUri',
                'folders'  => [$folder],
                'assets'   => [
                    'style' => 'url1',
                    'js'    => 'url2'
                ],
            ]
        );

        ($this->controller)();
    }

    public function getController(): AbstractController
    {
        return new IndexController($this->assetLoader, $this->routeService, $this->folderOutputProvider);
    }
}
