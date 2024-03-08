<?php

declare(strict_types=1);

namespace FD\LogViewer\Controller;

use FD\LogViewer\Service\File\LogFileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteFileController extends AbstractController implements RemoteHostProxyInterface
{
    public function __construct(private readonly Filesystem $filesystem, private readonly LogFileService $fileService)
    {
    }

    public function __invoke(string $identifier): Response
    {
        $file = $this->fileService->findFileByIdentifier($identifier);
        if ($file === null) {
            throw new NotFoundHttpException(sprintf('Log file with id `%s` not found.', $identifier));
        }

        if ($file->folder->collection->config->deletable === false) {
            throw new AccessDeniedHttpException(sprintf('Log file with id `%s` is not allowed to be deleted.', $identifier));
        }

        $this->filesystem->remove($file->path);

        return new JsonResponse(['success' => true]);
    }
}
