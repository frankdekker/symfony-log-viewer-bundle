<?php
declare(strict_types=1);

namespace FD\LogViewer\Controller;

use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Routing\RouteService;
use FD\LogViewer\Service\Folder\LogFolderOutputProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class IndexController extends AbstractController
{
    public function __construct(
        private readonly RouteService $routeService,
        private readonly LogFolderOutputProvider $folderOutputProvider
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

        return $this->render('@SymfonyLogViewer/index.html.twig', ['base_uri' => $baseUri, 'folders' => $folders]);
    }
}
