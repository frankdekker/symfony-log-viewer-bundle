<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Controller;

use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Service\LogFolderOutputProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FoldersController extends AbstractController
{
    public function __construct(private readonly LogFolderOutputProvider $folderOutputProvider)
    {
    }

    public function __invoke(Request $request): Response
    {
        $direction = DirectionEnum::tryFrom($request->query->get('direction', DirectionEnum::Desc->value)) ?? DirectionEnum::Desc;

        return $this->json($this->folderOutputProvider->provide($direction));
    }
}
