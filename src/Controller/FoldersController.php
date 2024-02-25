<?php
declare(strict_types=1);

namespace FD\LogViewer\Controller;

use FD\LogViewer\Entity\Output\DirectionEnum;
use FD\LogViewer\Service\Host\HostServiceBridge;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

class FoldersController extends AbstractController
{
    public function __construct(private readonly HostServiceBridge $hostServiceBridge)
    {
    }

    /**
     * @throws Throwable
     */
    public function __invoke(Request $request): JsonResponse
    {
        $host      = $request->query->get('host', 'localhost');
        $direction = DirectionEnum::tryFrom($request->query->get('direction', DirectionEnum::Desc->value)) ?? DirectionEnum::Desc;

        return new JsonResponse($this->hostServiceBridge->getLogFolders($host, $direction), json: true);
    }
}
