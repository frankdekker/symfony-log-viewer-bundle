<?php
declare(strict_types=1);

namespace FD\LogViewer\Controller;

use Exception;
use FD\LogViewer\Service\File\LogFileService;
use FD\LogViewer\Service\File\LogQueryDtoFactory;
use FD\LogViewer\Service\File\LogRecordsOutputProvider;
use FD\LogViewer\Service\Parser\InvalidDateTimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LogRecordsController extends AbstractController implements RemoteHostProxyInterface
{
    public function __construct(
        private readonly LogFileService $fileService,
        private readonly LogQueryDtoFactory $queryDtoFactory,
        private readonly LogRecordsOutputProvider $outputProvider,
    ) {
    }

    /**
     * @throws Exception
     */
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $logQuery = $this->queryDtoFactory->create($request);
        } catch (InvalidDateTimeException $exception) {
            throw new BadRequestHttpException('Invalid date.', $exception);
        }

        $files = $this->fileService->findFileByIdentifiers($logQuery->fileIdentifiers);
        if (count($files) === 0) {
            throw new NotFoundHttpException(sprintf('No log files found with id(s) `%s`.', implode(',', $logQuery->fileIdentifiers)));
        }

        if (count($files) === 1) {
            $output = $this->outputProvider->provide(reset($files), $logQuery);
        } else {
            $output = $this->outputProvider->provideForFiles($files, $logQuery);
        }

        return new JsonResponse($output);
    }
}
