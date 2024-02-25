<?php
declare(strict_types=1);

namespace FD\LogViewer\Controller;

use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Routing\RouteService;
use FD\LogViewer\Service\Folder\LogFolderOutputProvider;
use FD\LogViewer\Service\Host\HostListProvider;
use FD\LogViewer\Service\JsonManifestAssetLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly ?string $homeRoute,
        private readonly JsonManifestAssetLoader $assetLoader,
        private readonly RouteService $routeService,
        private readonly LogFolderOutputProvider $folderOutputProvider,
        private readonly HostListProvider $hostListProvider
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(): Response
    {
        // retrieve base uri from route
        $baseUri = $this->routeService->getBaseUri();

        // retrieve all log files and folders
        $folders = $this->folderOutputProvider->provide(DirectionEnum::Desc);

        return $this->render(
            '@FDLogViewer/index.html.twig',
            [
                'base_uri'   => $baseUri,
                'home_route' => $this->homeRoute,
                'folders'    => $folders,
                'hosts'      => $this->hostListProvider->getHosts(),
                'assets'     => [
                    'style' => $this->assetLoader->getUrl('style.css'),
                    'js'    => $this->assetLoader->getUrl('src/main.ts')
                ],
            ]
        );
    }
}
