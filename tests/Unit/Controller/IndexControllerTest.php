<?php
declare(strict_types=1);

namespace FD\LogViewer\Tests\Unit\Controller;

use DR\PHPUnitExtensions\Symfony\AbstractControllerTestCase;
use FD\LogViewer\Controller\IndexController;
use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Entity\Output\LogFolderOutput;
use FD\LogViewer\Routing\RouteService;
use FD\LogViewer\Service\Folder\LogFolderOutputProvider;
use FD\LogViewer\Service\Host\HostProvider;
use FD\LogViewer\Service\JsonManifestAssetLoader;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    private HostProvider&MockObject $hostProvider;

    protected function setUp(): void
    {
        $this->assetLoader          = $this->createMock(JsonManifestAssetLoader::class);
        $this->routeService         = $this->createMock(RouteService::class);
        $this->folderOutputProvider = $this->createMock(LogFolderOutputProvider::class);
        $this->hostProvider         = $this->createMock(HostProvider::class);
        parent::setUp();
    }

    /**
     * @throws Throwable
     */
    public function testInvoke(): void
    {
        $request = self::createStub(Request::class);
        $request->method('getBasePath')->willReturn('/base-path');
        $folder = new LogFolderOutput('identifier', 'path', true, false, 123456, []);

        $this->routeService->expects(self::once())->method('getBaseUri')->willReturn('/base-uri');
        $this->folderOutputProvider->expects(self::once())->method('provide')->with(DirectionEnum::Desc)->willReturn([$folder]);
        $this->hostProvider->expects(self::once())->method('getHosts')->willReturn(['host' => 'host']);
        $this->assetLoader->expects(self::exactly(2))
            ->method('getUrl')
            ->with(...consecutive(['style.css'], ['src/main.ts']))
            ->willReturn('/url1', '/url2');

        $this->expectRender(
            '@FDLogViewer/index.html.twig',
            [
                'base_uri'   => '/base-path/base-uri',
                'home_route' => 'home',
                'folders'    => [$folder],
                'assets'     => ['style' => '/base-path/url1', 'js' => '/base-path/url2'],
                'hosts'      => ['host' => 'host']
            ]
        );

        ($this->controller)($request);
    }

    public function getController(): AbstractController
    {
        return new IndexController('home', $this->assetLoader, $this->routeService, $this->folderOutputProvider, $this->hostProvider);
    }
}
