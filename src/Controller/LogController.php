<?php
declare(strict_types=1);

namespace FD\SymfonyLogViewerBundle\Controller;

use FD\SymfonyLogViewerBundle\Entity\LogFilter;
use FD\SymfonyLogViewerBundle\Entity\Output\DirectionEnum;
use FD\SymfonyLogViewerBundle\Service\LogFileService;
use FD\SymfonyLogViewerBundle\Service\LogFolderOutputFactory;
use FD\SymfonyLogViewerBundle\Service\LogLineOutputFactory;
use FD\SymfonyLogViewerBundle\Service\LogParser;
use FD\SymfonyLogViewerBundle\Service\MonoLogLineParser;
use FD\SymfonyLogViewerBundle\Util\Utils;
use SplFileInfo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LogController extends AbstractController
{
    public function __construct(
        private readonly LogFileService $fileService,
        private readonly LogParser $logParser,
        private readonly LogFolderOutputFactory $folderOutputFactory,
        private readonly iterable $loggerLocator,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $channels = [];
        foreach ($this->loggerLocator as $logger) {
            $channels[$logger->getName()] = $logger->getName();
        }
        ksort($channels);

        $logLevels = [
            "emergency" => "Emergency",
            "alert"     => "Alert",
            "critical"  => "Critical",
            "error"     => "Error",
            "warning"   => "Warning",
            "notice"    => "Notice",
            "info"      => "Info",
            "debug"     => "Debug"
        ];

        $fileIdentifier   = $request->query->get('file', '');
        $offset           = $request->query->get('offset');
        $offset           = $offset === null || $offset === '0' ? null : (int)$offset;
        $query            = $request->query->get('query', '');
        $direction        = DirectionEnum::from($request->query->get('direction', 'desc'));
        $selectedLevels   = array_filter(explode(',', $request->query->get('levels', '')), static fn($level) => $level !== '');
        $selectedChannels = array_filter(explode(',', $request->query->get('channels', '')), static fn($channel) => $channel !== '');
        $perPage          = $request->query->getInt('per_page', 25);

        if (count($selectedLevels) === count($logLevels)) {
            $selectedLevels = [];
        }
        if (count($selectedChannels) === count($channels)) {
            $selectedChannels = [];
        }

        $file = $this->fileService->findFileByIdentifier($fileIdentifier);
        if ($file === null) {
            throw new NotFoundHttpException(sprintf('Log file with id `%s` not found.', $fileIdentifier));
        }

        $filter = new LogFilter($selectedLevels, $selectedChannels, $query);

        $logIndex  = $this->logParser->parse(new SplFileInfo($file->getPath()), new MonoLogLineParser(), $direction, $perPage, $offset, $filter);
        $startTime = (float)$request->server->get('REQUEST_TIME_FLOAT');

        return $this->json(
            [
                'file'        => $this->folderOutputFactory->createFromFile($file),
                "levels"      => [
                    "choices"  => $logLevels,
                    "selected" => count($selectedLevels) === 0 ? array_keys($logLevels) : $selectedLevels
                ],
                "channels"    => [
                    "choices"  => $channels,
                    "selected" => count($selectedChannels) === 0 ? array_keys($channels) : $selectedChannels
                ],
                'logs'        => $logIndex->getLines(),
                'paginator'   => $logIndex->getPaginator(),
                'performance' => [
                    'memoryUsage' => Utils::bytesForHumans(memory_get_usage()),
                    'requestTime' => round((microtime(true) - $startTime) * 1000) . 'ms',
                    'version'     => '1.0.0', // TODO version
                ]
            ]
        );
    }
}
