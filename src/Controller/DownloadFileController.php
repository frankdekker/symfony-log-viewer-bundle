<?php

declare(strict_types=1);

namespace FD\LogViewer\Controller;

use FD\LogViewer\Service\File\LogFileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DownloadFileController extends AbstractController implements RemoteHostProxyInterface
{
    public function __construct(private readonly LogFileService $fileService)
    {
    }

    public function __invoke(string $identifier): BinaryFileResponse
    {
        $file = $this->fileService->findFileByIdentifier($identifier);
        if ($file === null) {
            throw new NotFoundHttpException(sprintf('Log file with id `%s` not found.', $identifier));
        }

        if ($file->folder->collection->config->downloadable === false) {
            throw new AccessDeniedHttpException(sprintf('Log file with id `%s` is not downloadable.', $identifier));
        }

        return (new BinaryFileResponse($file->path, headers: ['Content-Type' => 'text/plain'], public: false))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, basename($file->path));
    }
}
