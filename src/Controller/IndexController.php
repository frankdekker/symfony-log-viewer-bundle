<?php
declare(strict_types=1);

namespace FD\LogViewer\Controller;

use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Routing\RouteService;
use FD\LogViewer\Service\Folder\LogFolderOutputProvider;
use FD\LogViewer\Service\Host\HostProvider;
use FD\LogViewer\Service\JsonManifestAssetLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly ?string $homeRoute,
        private readonly JsonManifestAssetLoader $assetLoader,
        private readonly RouteService $routeService,
        private readonly LogFolderOutputProvider $folderOutputProvider,
        private readonly HostProvider $hostListProvider
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(Request $request): Response
    {
        // retrieve project base path
        $basePath = $request->getBasePath();

        // retrieve base uri from route
        $baseUri = $this->routeService->getBaseUri();

        // retrieve all log files and folders
        $folders = $this->folderOutputProvider->provide(DirectionEnum::Desc);

        return $this->render(
            '@FDLogViewer/index.html.twig',
            [
                'base_uri'   => $basePath . $baseUri,
                'home_route' => $this->homeRoute,
                'folders'    => $folders,
                'hosts'      => $this->hostListProvider->getHosts(),
                'assets'     => [
                    'style' => $basePath . $this->assetLoader->getUrl('style.css'),
                    'js'    => $basePath . $this->assetLoader->getUrl('src/main.ts')
                ],
            ]
        );
    }
}
