<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Controller;

use FD\SymfonyLogViewerBundle\Service\File\LogFileService;
use FD\SymfonyLogViewerBundle\Service\File\LogQueryDtoFactory;
use FD\SymfonyLogViewerBundle\Service\File\LogRecordsOutputProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LogRecordsController extends AbstractController
{
    public function __construct(
        private readonly LogFileService $fileService,
        private readonly LogQueryDtoFactory $queryDtoFactory,
        private readonly LogRecordsOutputProvider $outputProvider,
    ) {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $logQuery = $this->queryDtoFactory->create($request);
        $file     = $this->fileService->findFileByIdentifier($logQuery->fileIdentifier);
        if ($file === null) {
            throw new NotFoundHttpException(sprintf('Log file with id `%s` not found.', $logQuery->fileIdentifier));
        }

        $output = $this->outputProvider->provide($file->folder->collection->config, $file, $logQuery);

        return new JsonResponse($output);
    }
}
