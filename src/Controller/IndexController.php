<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Controller;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Service\LogFolderOutputProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Throwable;
use Twig\Environment;

class IndexController
{
    public function __construct(
        private readonly LogFolderOutputProvider $folderOutputProvider,
        private readonly Environment $twig,
        private readonly RouterInterface $router
    ) {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(Request $request): Response
    {
        // retrieve base uri from route
        $baseUri = $this->router->getRouteCollection()->get(self::class . '.base')?->getPath();
        assert($baseUri !== null);

        // retrieve all log files and folders
        $folders = $this->folderOutputProvider->provide(DirectionEnum::Desc);

        return new Response($this->twig->render('@SymfonyLogViewer/index.html.twig', ['base_uri' => $baseUri, 'folders' => $folders]));
    }
}
