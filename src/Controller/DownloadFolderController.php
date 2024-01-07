<?php

declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Controller;

use FD\SymfonyLogViewerBundle\Service\File\LogFileService;
use FD\SymfonyLogViewerBundle\Service\Folder\ZipArchiveFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DownloadFolderController extends AbstractController
{
    public function __construct(private readonly LogFileService $folderService, private readonly ZipArchiveFactory $zipArchiveFactory)
    {
    }

    public function __invoke(string $identifier): BinaryFileResponse
    {
        $folder = $this->folderService->findFolderByIdentifier($identifier);
        if ($folder === null) {
            throw new NotFoundHttpException(sprintf('Log folder with id `%s` not found.', $identifier));
        }

        if ($folder->collection->config->downloadable === false) {
            throw new AccessDeniedHttpException(sprintf('Log folder with id `%s` is not downloadable.', $identifier));
        }

        $zipFile = $this->zipArchiveFactory->createFromFolder($folder);

        return (new BinaryFileResponse($zipFile, headers: ['Content-Type' => 'application/zip'], public: false))
            ->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, basename($folder->path . '.zip'));
    }
}
