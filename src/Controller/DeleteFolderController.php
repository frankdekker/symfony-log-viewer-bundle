<?php

declare(strict_types=1);

namespace FD\LogViewer\Controller;

use FD\LogViewer\Service\File\LogFileService;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DeleteFolderController extends AbstractController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    public function __construct(private readonly LogFileService $fileService)
    {
    }

    public function __invoke(string $identifier): Response
    {
        $folder = $this->fileService->findFolderByIdentifier($identifier);
        if ($folder === null) {
            throw new NotFoundHttpException(sprintf('Log folder with id `%s` not found.', $identifier));
        }

        if ($folder->collection->config->deletable === false) {
            throw new AccessDeniedHttpException(sprintf('Log folder with id `%s` is not allowed to be deleted.', $identifier));
        }

        foreach ($folder->getFiles() as $file) {
            if (@unlink($file->path) === false) {
                $this->logger?->notice(
                    'Log file with id `{id}` ({file}) is not deletable (no-write-access).',
                    ['id' => $identifier, 'file' => $file->path]
                );
            }
        }

        return new JsonResponse(['success' => true]);
    }
}